<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::with('categories')
            ->where('status', \App\Enums\EventStatus::Published)
            ->orderBy('event_date', 'asc')
            ->get();

        return view('welcome', compact('events'));
    }
}
