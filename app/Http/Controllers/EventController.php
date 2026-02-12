<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Request $request, $slug)
    {
        $query = Event::where('slug', $slug);

        $user = auth()->user();
        $isAdmin = $user && in_array($user->role->value, ['super-admin', 'admin', 'organizer']);

        if ($isAdmin) {
            $query->whereIn('status', [\App\Enums\EventStatus::Published, \App\Enums\EventStatus::Closed, \App\Enums\EventStatus::Draft]);
        } else {
            $query->whereIn('status', [\App\Enums\EventStatus::Published, \App\Enums\EventStatus::Closed]);
        }

        $event = $query->firstOrFail();

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
