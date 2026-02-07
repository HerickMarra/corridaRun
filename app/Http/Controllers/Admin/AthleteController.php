<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Mail\DynamicMail;
use Illuminate\Support\Facades\Mail;
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
        $templates = EmailTemplate::where('is_active', true)->orderBy('name')->get();

        return view('admin.athletes.index', compact('athletes', 'templates'));
    }

    public function sendEmail(Request $request, User $athlete)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id'
        ]);

        $template = EmailTemplate::findOrFail($request->template_id);

        try {
            Mail::to($athlete->email)->send(new DynamicMail($template, [
                'nome' => $athlete->name,
                'email' => $athlete->email,
                // Outras variáveis podem ser adicionadas aqui se houver contexto
            ]));

            return response()->json(['success' => true, 'message' => 'E-mail enviado com sucesso!']);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar e-mail manual: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao enviar e-mail.'], 500);
        }
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
