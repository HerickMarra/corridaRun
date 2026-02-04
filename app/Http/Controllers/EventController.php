<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)
            ->whereIn('status', [\App\Enums\EventStatus::Published, \App\Enums\EventStatus::Closed])
            ->firstOrFail();

        $kitHash = $request->get('kit');

        $categories = $event->categories()
            ->where(function ($query) use ($kitHash) {
                $query->where('is_public', true);
                if ($kitHash) {
                    $query->orWhere('access_hash', $kitHash);
                }
            })
            ->orderBy('sort_order')
            ->get();

        $event->setRelation('categories', $categories);

        return view('events.show', compact('event'));
    }
}
