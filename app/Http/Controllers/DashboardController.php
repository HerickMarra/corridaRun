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
        $subscriptions = \App\Models\Ticket::whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('orderItem.category.event')->get();

        return view('client.dashboard', compact('subscriptions', 'user'));
    }
}
