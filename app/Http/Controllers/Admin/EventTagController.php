<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventTagController extends Controller
{
    public function index()
    {
        $tags = EventTag::orderBy('name')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_tags,name',
            'color_hex' => 'required|string|max:7',
        ]);

        EventTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color_hex' => $request->color_hex,
        ]);

        return redirect()->back()->with('success', 'Tag criada com sucesso!');
    }

    public function update(Request $request, EventTag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_tags,name,' . $tag->id,
            'color_hex' => 'required|string|max:7',
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color_hex' => $request->color_hex,
        ]);

        return redirect()->back()->with('success', 'Tag atualizada!');
    }

    public function destroy(EventTag $tag)
    {
        $tag->delete();
        return redirect()->back()->with('success', 'Tag removida!');
    }
}
