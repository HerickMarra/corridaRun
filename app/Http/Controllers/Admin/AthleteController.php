<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', UserRole::Client)
            ->withCount('orders');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%");
            });
        }

        $athletes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.athletes.index', compact('athletes'));
    }

    public function show(User $athlete)
    {
        $athlete->load([
            'orders.items.category.event.customFields',
        ]);

        return response()->json([
            'athlete' => $athlete,
            'registrations' => $athlete->orders->flatMap(function ($order) {
                return $order->items->map(function ($item) {
                    $event = $item->category->event;
                    $responses = [];

                    if ($item->custom_responses) {
                        foreach ($item->custom_responses as $fieldId => $value) {
                            $field = $event->customFields->find($fieldId);
                            if ($field) {
                                $responses[] = [
                                    'label' => $field->label,
                                    'value' => $value
                                ];
                            }
                        }
                    }

                    return [
                        'event_name' => $event->name,
                        'category_name' => $item->category->name,
                        'date' => $item->created_at->format('d/m/Y'),
                        'status' => $item->status,
                        'price' => $item->price,
                        'custom_responses' => $responses
                    ];
                });
            })
        ]);
    }

    public function edit(User $athlete)
    {
        return view('admin.athletes.edit', compact('athlete'));
    }

    public function update(Request $request, User $athlete)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $athlete->id,
            'cpf' => 'nullable|string|max:14',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'zip_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
        ]);

        $athlete->update($request->all());

        return redirect()->route('admin.athletes.index')->with('success', 'Cadastro do atleta atualizado com sucesso!');
    }
}
