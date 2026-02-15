<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('categories')
            ->where('status', 'published')
            ->orderBy('event_date', 'asc');

        // Se não houver filtro de mês, mostra do dia atual em diante
        if (!$request->filled('month')) {
            $query->where('event_date', '>=', now()->startOfDay());
        }

        // Filtrar por mês
        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereMonth('event_date', $month);
            // Garante que pegue eventos do ano atual ou futuro se o mês já passou
            $query->whereYear('event_date', '>=', now()->year);
        }

        $events = $query->get();

        // Agrupar eventos por mês
        $eventsByMonth = $events->groupBy(function ($event) {
            return $event->event_date->format('Y-m');
        });

        return view('calendar', compact('eventsByMonth'));
    }
}
