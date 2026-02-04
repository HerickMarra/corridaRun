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
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc');

        // Filter by state
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // Filter by month
        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereMonth('event_date', $month);
        }

        $events = $query->get();

        // Group events by month
        $eventsByMonth = $events->groupBy(function ($event) {
            return $event->event_date->format('Y-m');
        });

        // Get unique states for filter
        $states = Event::where('status', 'published')
            ->distinct()
            ->pluck('state')
            ->sort();

        return view('calendar', compact('eventsByMonth', 'states'));
    }
}
