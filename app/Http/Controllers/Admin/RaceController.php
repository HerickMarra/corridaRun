<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Event::withCount('categories');

        // O organizador vê apenas as provas vinculadas a ele
        if ($user->role === \App\Enums\UserRole::Organizer) {
            $query->whereIn('id', $user->managedEvents->pluck('id'));
        }

        // Busca por nome
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ordenação - mais recentes primeiro
        $query->latest('created_at');

        // Paginação
        $events = $query->paginate(15)->withQueryString();

        return view('admin.corridas.index', compact('events'));
    }

    public function create()
    {
        $tags = \App\Models\EventTag::all();
        return view('admin.corridas.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'max_participants' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,closed,cancelled',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*.max_participants' => 'required|integer|min:1',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.label' => 'required|string|max:255',
            'custom_fields.*.type' => 'required|in:text,number,select,textarea',
            'custom_fields.*.options' => 'nullable|string',
            'custom_fields.*.is_required' => 'nullable|boolean',
            'regulation' => 'nullable|string',
            'nutrition' => 'nullable|in:not_informed,none,partial,complete',
            'hydration' => 'nullable|in:not_informed,none,partial,complete',
        ]);



        $bannerPath = 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e';
        if ($request->hasFile('banner_image')) {
            $bannerPath = '/storage/' . $request->file('banner_image')->store('events', 'public');
        }

        // Gerar slug único
        $baseSlug = \Illuminate\Support\Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (Event::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $event = Event::create([
            'name' => $request->name,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'location' => $request->location,
            'city' => $request->city,
            'state' => $request->state,
            'max_participants' => $request->max_participants,
            'status' => $request->status,
            'banner_image' => $bannerPath,
            'regulation' => $request->regulation,
            'nutrition' => $request->nutrition ?? 'not_informed',
            'hydration' => $request->hydration ?? 'not_informed',
        ]);

        foreach ($request->categories as $index => $categoryData) {
            $isPublic = isset($categoryData['is_public']) ? (bool) $categoryData['is_public'] : true;
            $event->categories()->create([
                'name' => $categoryData['name'],
                'distance' => $categoryData['distance'],
                'price' => $categoryData['price'],
                'max_participants' => $categoryData['max_participants'],
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

        if ($request->has('tags')) {
            $event->tags()->sync($request->tags);
        }

        return redirect()->route('admin.corridas.index')->with('success', 'Corrida criada com sucesso!');
    }

    public function edit(Event $event)
    {
        // Organizadores não podem editar provas
        if (auth()->user()->role === \App\Enums\UserRole::Organizer) {
            abort(403, 'Você não tem permissão para editar esta corrida.');
        }

        $event->load(['categories', 'customFields', 'coupons', 'routes', 'tags']);
        $tags = \App\Models\EventTag::all();
        return view('admin.corridas.edit', compact('event', 'tags'));
    }

    public function update(Request $request, Event $event)
    {
        // Organizadores não podem editar provas
        if (auth()->user()->role === \App\Enums\UserRole::Organizer) {
            abort(403, 'Você não tem permissão para editar esta corrida.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'max_participants' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,closed,cancelled',
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
            'nutrition' => 'nullable|in:not_informed,none,partial,complete',
            'hydration' => 'nullable|in:not_informed,none,partial,complete',
        ]);


        if ($request->hasFile('banner_image')) {
            $bannerPath = '/storage/' . $request->file('banner_image')->store('events', 'public');
            $event->banner_image = $bannerPath;
        }

        $event->update([
            'name' => $request->name,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'status' => $request->status,
            'location' => $request->location,
            'city' => $request->city,
            'state' => $request->state,
            'max_participants' => $request->max_participants,
            'regulation' => $request->regulation,
            'nutrition' => $request->nutrition ?? 'not_informed',
            'hydration' => $request->hydration ?? 'not_informed',
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

        // Sync Tags
        $event->tags()->sync($request->tags ?? []);

        return redirect()->route('admin.corridas.index')->with('success', 'Corrida atualizada com sucesso!');
    }

    public function destroy(Event $event)
    {
        // Organizadores não podem excluir provas
        if (auth()->user()->role === \App\Enums\UserRole::Organizer) {
            abort(403, 'Você não tem permissão para excluir esta corrida.');
        }

        $event->delete();
        return redirect()->route('admin.corridas.index')->with('success', 'Corrida movida para a lixeira!');
    }

    public function dashboard(Event $event)
    {
        $user = auth()->user();

        // Verificar permissão do organizador
        if ($user->role === \App\Enums\UserRole::Organizer) {
            if (!$user->managedEvents->contains($event->id)) {
                abort(403, 'Você não tem permissão para gerenciar esta corrida.');
            }
        }

        $event->load(['categories', 'orderItems.order', 'coupons']);

        // KPIs
        $totalInscriptions = $event->orderItems()->where('order_items.status', 'paid')->count();
        $totalRevenue = (float) $event->orderItems()->where('order_items.status', 'paid')->sum('order_items.price');
        $serviceFee = $totalRevenue * 0.07;
        $avgTicket = $totalInscriptions > 0 ? ($totalRevenue / $totalInscriptions) : 0;

        // Sales by Day - Intelligent Dynamic Range
        $categoryIds = $event->categories->pluck('id');
        $salesQuery = \App\Models\OrderItem::whereIn('category_id', $categoryIds)
            ->where('order_items.status', 'paid');

        $firstSale = $salesQuery->min('order_items.created_at');
        $lastSale = $salesQuery->max('order_items.created_at');

        if ($firstSale) {
            $startDate = \Carbon\Carbon::parse($firstSale)->startOfDay();
            $endDate = \Carbon\Carbon::parse($lastSale)->endOfDay();

            // If the range is very short (e.g., same day), show at least 7 days for perspective
            if ($startDate->diffInDays($endDate) < 7) {
                $startDate = $startDate->copy()->subDays(7);
            }
        } else {
            $startDate = now()->subDays(30)->startOfDay();
            $endDate = now()->endOfDay();
        }

        $rawSales = \App\Models\OrderItem::whereIn('category_id', $categoryIds)
            ->where('order_items.status', 'paid')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->selectRaw('DATE(order_items.created_at) as date, count(*) as count, sum(order_items.price) as revenue')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $salesByDay = collect();
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayData = $rawSales->get($dateStr);

            $salesByDay->push([
                'date' => $dateStr,
                'count' => $dayData ? $dayData->count : 0,
                'revenue' => (float) ($dayData ? $dayData->revenue : 0)
            ]);
            $currentDate->addDay();
        }

        // Category Stats
        $categoryStats = $event->categories->map(function ($category) {
            $sold = $category->orderItems()->where('order_items.status', 'paid')->count();
            return [
                'name' => $category->name,
                'sold' => $sold,
                'max' => $category->max_participants,
                'percent' => $category->max_participants > 0 ? min(100, ($sold / $category->max_participants) * 100) : 0,
                'revenue' => (float) $category->orderItems()->where('order_items.status', 'paid')->sum('order_items.price')
            ];
        });

        // Recent Inscriptions
        $recentInscriptions = $event->orderItems()
            ->with('order.user')
            ->where('order_items.status', 'paid')
            ->latest('order_items.created_at')
            ->take(10)
            ->get();

        return view('admin.corridas.dashboard', compact(
            'event',
            'totalInscriptions',
            'totalRevenue',
            'serviceFee',
            'avgTicket',
            'salesByDay',
            'categoryStats',
            'recentInscriptions'
        ));
    }

    public function exportParticipants(Event $event)
    {
        $user = auth()->user();
        if ($user->role === \App\Enums\UserRole::Organizer) {
            if (!$user->managedEvents->contains($event->id)) {
                abort(403);
            }
        }

        $fileName = 'inscritos-' . \Illuminate\Support\Str::slug($event->name) . '-' . now()->format('Y-m-d-His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\InscriptionsExport($event), $fileName);
    }

    public function search(Request $request)
    {
        $term = $request->query('q');

        $query = Event::query();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('state', 'like', "%{$term}%");
            });
        }

        $events = $query->latest('event_date')
            ->take(10)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'city' => $event->city,
                    'state' => $event->state,
                    'event_date' => $event->event_date->format('d/m/Y'),
                    'banner_image' => $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e'
                ];
            });

        return response()->json($events);
    }
}
