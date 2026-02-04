<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index()
    {
        $events = Event::withCount('categories')->latest()->get();
        return view('admin.corridas.index', compact('events'));
    }

    public function create()
    {
        return view('admin.corridas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'max_participants' => 'required|integer|min:1',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*.max_participants' => 'required|integer|min:1',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.label' => 'required|string|max:255',
            'custom_fields.*.type' => 'required|in:text,number,select,textarea',
            'custom_fields.*.options' => 'nullable|string',
            'custom_fields.*.is_required' => 'nullable|boolean',
            'regulation' => 'nullable|string',
        ]);

        $bannerPath = 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e';
        if ($request->hasFile('banner_image')) {
            $bannerPath = '/storage/' . $request->file('banner_image')->store('events', 'public');
        }

        $event = Event::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'event_date' => $request->event_date,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'location' => $request->location,
            'city' => $request->city,
            'state' => $request->state,
            'max_participants' => $request->max_participants,
            'status' => 'published',
            'banner_image' => $bannerPath,
            'regulation' => $request->regulation,
        ]);

        foreach ($request->categories as $index => $categoryData) {
            $isPublic = isset($categoryData['is_public']) ? (bool) $categoryData['is_public'] : true;
            $event->categories()->create([
                'name' => $categoryData['name'],
                'distance' => $categoryData['distance'],
                'price' => $categoryData['price'],
                'max_participants' => $categoryData['max_participants'],
                'available_tickets' => $categoryData['max_participants'],
                'status' => 'active',
                'sort_order' => $index,
                'is_public' => $isPublic,
                'access_hash' => $isPublic ? null : \Illuminate\Support\Str::random(16),
                'items_included' => !empty($categoryData['items_included']) ? array_filter(array_map('trim', explode(',', $categoryData['items_included']))) : null,
            ]);
        }

        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $index => $fieldData) {
                $event->customFields()->create([
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'options' => $fieldData['options'] ? array_map('trim', explode(',', $fieldData['options'])) : null,
                    'is_required' => isset($fieldData['is_required']) ? (bool) $fieldData['is_required'] : false,
                    'sort_order' => $index,
                ]);
            }
        }

        if ($request->has('routes')) {
            foreach ($request->routes as $routeData) {
                if (!empty($routeData['path'])) {
                    $event->routes()->create([
                        'name' => $routeData['name'],
                        'color' => $routeData['color'],
                        'path' => json_decode($routeData['path'], true),
                        'markers' => !empty($routeData['markers']) ? json_decode($routeData['markers'], true) : null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.corridas.index')->with('success', 'Corrida criada com sucesso!');
    }

    public function edit(Event $event)
    {
        $event->load(['categories', 'customFields', 'coupons', 'routes']);
        return view('admin.corridas.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'max_participants' => 'required|integer|min:1',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*.id' => 'nullable|exists:categories,id',
            'categories.*.max_participants' => 'required|integer|min:1',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.id' => 'nullable|exists:event_custom_fields,id',
            'custom_fields.*.label' => 'required|string|max:255',
            'custom_fields.*.type' => 'required|in:text,number,select,textarea',
            'custom_fields.*.options' => 'nullable|string',
            'custom_fields.*.is_required' => 'nullable|boolean',
            'coupons' => 'nullable|array',
            'coupons.*.id' => 'nullable|exists:event_coupons,id',
            'coupons.*.code' => 'required|string|max:20',
            'coupons.*.type' => 'required|in:fixed,percent',
            'coupons.*.value' => 'required|numeric|min:0',
            'coupons.*.usage_limit' => 'nullable|integer|min:1',
            'coupons.*.is_active' => 'nullable|boolean',
            'regulation' => 'nullable|string',
        ]);

        if ($request->hasFile('banner_image')) {
            $bannerPath = '/storage/' . $request->file('banner_image')->store('events', 'public');
            $event->banner_image = $bannerPath;
        }

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'location' => $request->location,
            'city' => $request->city,
            'state' => $request->state,
            'max_participants' => $request->max_participants,
            'regulation' => $request->regulation,
        ]);

        // Sync Categories
        $categoryIds = collect($request->categories)->pluck('id')->filter()->toArray();
        $event->categories()->whereNotIn('id', $categoryIds)->delete();

        if ($request->has('categories')) {
            foreach ($request->categories as $index => $categoryData) {
                $isPublic = isset($categoryData['is_public']) ? (bool) $categoryData['is_public'] : true;

                if (isset($categoryData['id'])) {
                    $category = $event->categories()->find($categoryData['id']);

                    // Se mudar de público para privado e não tiver hash, gera um
                    $hash = $category->access_hash;
                    if (!$isPublic && !$hash) {
                        $hash = \Illuminate\Support\Str::random(16);
                    } elseif ($isPublic) {
                        $hash = null;
                    }

                    $category->update([
                        'name' => $categoryData['name'],
                        'distance' => $categoryData['distance'],
                        'price' => $categoryData['price'],
                        'max_participants' => $categoryData['max_participants'],
                        'sort_order' => $index,
                        'is_public' => $isPublic,
                        'access_hash' => $hash,
                        'items_included' => !empty($categoryData['items_included']) ? array_filter(array_map('trim', explode(',', $categoryData['items_included']))) : null,
                    ]);
                } else {
                    $event->categories()->create([
                        'name' => $categoryData['name'],
                        'distance' => $categoryData['distance'],
                        'price' => $categoryData['price'],
                        'max_participants' => $categoryData['max_participants'],
                        'available_tickets' => $categoryData['max_participants'],
                        'status' => 'active',
                        'sort_order' => $index,
                        'is_public' => $isPublic,
                        'access_hash' => $isPublic ? null : \Illuminate\Support\Str::random(16),
                        'items_included' => !empty($categoryData['items_included']) ? array_filter(array_map('trim', explode(',', $categoryData['items_included']))) : null,
                    ]);
                }
            }
        }

        // Sync Custom Fields
        $fieldIds = collect($request->custom_fields)->pluck('id')->filter()->toArray();
        $event->customFields()->whereNotIn('id', $fieldIds)->delete();

        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $index => $fieldData) {
                if (isset($fieldData['id'])) {
                    $field = $event->customFields()->find($fieldData['id']);
                    $field->update([
                        'label' => $fieldData['label'],
                        'type' => $fieldData['type'],
                        'options' => $fieldData['options'] ? (is_array($fieldData['options']) ? $fieldData['options'] : array_map('trim', explode(',', $fieldData['options']))) : null,
                        'is_required' => isset($fieldData['is_required']) ? (bool) $fieldData['is_required'] : false,
                        'sort_order' => $index,
                    ]);
                } else {
                    $event->customFields()->create([
                        'label' => $fieldData['label'],
                        'type' => $fieldData['type'],
                        'options' => $fieldData['options'] ? array_map('trim', explode(',', $fieldData['options'])) : null,
                        'is_required' => isset($fieldData['is_required']) ? (bool) $fieldData['is_required'] : false,
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        // Sync Coupons
        $couponIds = collect($request->coupons)->pluck('id')->filter()->toArray();
        $event->coupons()->whereNotIn('id', $couponIds)->delete();

        if ($request->has('coupons')) {
            foreach ($request->coupons as $couponData) {
                if (isset($couponData['id'])) {
                    $coupon = $event->coupons()->find($couponData['id']);
                    $coupon->update([
                        'code' => strtoupper($couponData['code']),
                        'type' => $couponData['type'],
                        'value' => $couponData['value'],
                        'usage_limit' => $couponData['usage_limit'],
                        'is_active' => isset($couponData['is_active']) ? (bool) $couponData['is_active'] : false,
                    ]);
                } else {
                    $event->coupons()->create([
                        'code' => strtoupper($couponData['code']),
                        'type' => $couponData['type'],
                        'value' => $couponData['value'],
                        'usage_limit' => $couponData['usage_limit'],
                        'is_active' => isset($couponData['is_active']) ? (bool) $couponData['is_active'] : false,
                    ]);
                }
            }
        }

        // Sync Routes
        $routeIds = collect($request->routes)->pluck('id')->filter()->toArray();
        $event->routes()->whereNotIn('id', $routeIds)->delete();

        if ($request->has('routes')) {
            foreach ($request->routes as $routeData) {
                if (empty($routeData['path']))
                    continue;

                $path = is_string($routeData['path']) ? json_decode($routeData['path'], true) : $routeData['path'];
                $markers = !empty($routeData['markers']) ? (is_string($routeData['markers']) ? json_decode($routeData['markers'], true) : $routeData['markers']) : null;

                if (isset($routeData['id'])) {
                    $route = $event->routes()->find($routeData['id']);
                    $route->update([
                        'name' => $routeData['name'],
                        'color' => $routeData['color'],
                        'path' => $path,
                        'markers' => $markers,
                    ]);
                } else {
                    $event->routes()->create([
                        'name' => $routeData['name'],
                        'color' => $routeData['color'],
                        'path' => $path,
                        'markers' => $markers,
                    ]);
                }
            }
        }

        return redirect()->route('admin.corridas.index')->with('success', 'Corrida atualizada com sucesso!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.corridas.index')->with('success', 'Corrida movida para a lixeira!');
    }
}
