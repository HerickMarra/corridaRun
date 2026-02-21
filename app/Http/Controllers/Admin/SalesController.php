<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with(['user', 'items.category.event']);

        // Filters
        if ($request->filled('event_id')) {
            $query->whereHas('items.category', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($qu) use ($request) {
                        $qu->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // KPIs
        $kpiQuery = clone $query;
        $stats = [
            'total_revenue' => (clone $kpiQuery)->where('status', OrderStatus::Paid)->sum('total_amount'),
            'orders_count' => (clone $kpiQuery)->count(),
            'paid_orders_count' => (clone $kpiQuery)->where('status', OrderStatus::Paid)->count(),
        ];
        $stats['average_ticket'] = $stats['paid_orders_count'] > 0 ? $stats['total_revenue'] / $stats['paid_orders_count'] : 0;

        // Chart Data
        $chartData = $this->getChartData($request);

        // Orders List
        $orders = $query->latest()->paginate(15)->withQueryString();

        // Data for Filters
        $events = Event::orderBy('name')->get();
        $statuses = OrderStatus::cases();

        return view('admin.sales.index', compact('orders', 'stats', 'chartData', 'events', 'statuses'));
    }

    private function getChartData(Request $request)
    {
        $days = 30;
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->subDays($days);
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : now();

        $query = Order::where('status', OrderStatus::Paid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        if ($request->filled('event_id')) {
            $query->whereHas('items.category', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        $sales = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        $labels = [];
        $data = [];

        $current = clone $startDate;
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->format('d/m');
            $data[] = (float) $sales->get($dateStr, 0);
            $current->addDay();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Vendas (R$)',
                    'data' => $data,
                    'borderColor' => '#0052FF',
                    'backgroundColor' => 'rgba(0, 82, 255, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.category.event.customFields', 'payments']);
        return view('admin.sales.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                // Update Order and Items Status
                $order->update(['status' => \App\Enums\OrderStatus::Cancelled]);
                $order->items()->update(['status' => \App\Enums\OrderStatus::Cancelled]);

                // Update Payments to cancelled if they are pending
                foreach ($order->payments as $payment) {
                    if ($payment->status === \App\Enums\PaymentStatus::Pending) {
                        $payment->update(['status' => \App\Enums\PaymentStatus::Rejected]); // Rejected/Cancelled
                    }
                }

                // Restore tickets
                foreach ($order->items as $item) {
                    if ($item->category) {
                        $item->category->increment('available_tickets');
                    }
                    if ($item->ticket) {
                        $item->ticket()->delete();
                    }
                }
            });

            return back()->with('success', 'Inscrição cancelada com sucesso. As vagas foram retornadas.');
        } catch (\Exception $e) {
            \Log::error('Erro ao cancelar inscrição manualmente: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao cancelar a inscrição.');
        }
    }
}
