<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::whereNot('role', UserRole::Client)
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $events = Event::latest('event_date')->take(5)->get();
        return view('admin.users.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Sync managed events if role is organizer
        if ($user->role === UserRole::Organizer) {
            $user->managedEvents()->sync($request->input('events', []));
        }

        return redirect()->route('admin.users.index')->with('success', 'Administrador criado com sucesso!');
    }

    public function show(User $user)
    {
        // Redireciona de forma silenciosa para evitar erros de browser cache/URL legada
        if ($user->role === UserRole::Client) {
            return redirect()->route('admin.athletes.index', ['search' => $user->email]);
        }

        return redirect()->route('admin.users.edit', $user->id);
    }

    public function edit(User $user)
    {
        if ($user->role === UserRole::Client) {
            abort(403, 'Você não pode editar um cliente através deste módulo.');
        }

        if ($user->role === UserRole::SuperAdmin && auth()->user()->role !== UserRole::SuperAdmin) {
            return back()->with('error', 'Apenas SuperAdmins podem editar outros SuperAdmins.');
        }

        // Get recent events + already assigned events
        $assignedEventIds = $user->managedEvents->pluck('id')->toArray();
        $recentEvents = Event::latest('event_date')->take(5)->get();

        $events = $recentEvents->merge($user->managedEvents)->unique('id');

        return view('admin.users.edit', compact('user', 'events'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === UserRole::Client) {
            abort(403);
        }

        if ($user->role === UserRole::SuperAdmin && auth()->user()->role !== UserRole::SuperAdmin) {
            return back()->with('error', 'Apenas SuperAdmins podem editar outros SuperAdmins.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync managed events if role is organizer
        if ($user->role === UserRole::Organizer) {
            $user->managedEvents()->sync($request->input('events', []));
        } else {
            // Remove all assignments if role changed from organizer to something else
            $user->managedEvents()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'Administrador atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        if ($user->role === UserRole::SuperAdmin && auth()->user()->role !== UserRole::SuperAdmin) {
            return back()->with('error', 'Apenas SuperAdmins podem excluir outros SuperAdmins.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Administrador excluído com sucesso!');
    }
}
