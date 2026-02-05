<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('client.dashboard');
    }

    public function admin()
    {
        $totalSales = \App\Models\Order::where('status', \App\Enums\OrderStatus::Paid)->sum('total_amount');
        $totalInscriptions = \App\Models\Ticket::count();
        $activeEvents = \App\Models\Event::where('status', 'published')->count();
        $recentSales = \App\Models\Order::with(['user', 'items.category.event'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalInscriptions',
            'activeEvents',
            'recentSales'
        ));
    }

    public function client()
    {
        $user = auth()->user();

        // Inscrições ativas (Tickets que pertencem a ordens do usuário)
        // Apenas eventos que ainda não aconteceram OU aconteceram há no máximo 2 dias
        $subscriptions = \App\Models\Ticket::whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereHas('orderItem.category.event', function ($query) {
                $query->where('event_date', '>=', now()->subDays(2)->startOfDay());
            })
            ->with('orderItem.category.event')
            ->get();

        // Eventos passados (Minha Jornada) - eventos que o usuário participou e já aconteceram
        $pastEventsQuery = \App\Models\Event::whereHas('categories.orderItems.order', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', \App\Enums\OrderStatus::Paid);
        })
            ->where(function ($query) {
                $query->where('event_date', '<', now())
                    ->orWhere('status', 'closed');
            })
            ->with(['categories'])
            ->orderBy('event_date', 'desc');

        $totalPastEvents = $pastEventsQuery->count();
        $pastEvents = $pastEventsQuery->limit(5)->get();

        return view('client.dashboard', compact('subscriptions', 'user', 'pastEvents', 'totalPastEvents'));
    }
}
