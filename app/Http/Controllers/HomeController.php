<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::with('categories')
            ->whereIn('status', [\App\Enums\EventStatus::Published, \App\Enums\EventStatus::Closed])
            ->orderByRaw("CASE WHEN status = 'published' THEN 0 ELSE 1 END")
            ->orderBy('event_date', 'asc')
            ->get();

        return view('welcome', compact('events'));
    }
}
