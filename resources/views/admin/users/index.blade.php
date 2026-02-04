@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Gestão de <span
                    class="text-primary">Administradores</span></h2>
            <p class="text-slate-500 text-sm font-medium">Gerencie o acesso e permissões da equipe interna.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-lg">add_moderator</span>
            Novo Administrador
        </a>
    </div>

    @if(session('success'))
        <div
            class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div
            class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-xl">error</span>
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nome / Email
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cargo / Role
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Criado em</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800 leading-none mb-1">{{ $user->name }}</p>
                                                    <p class="text-xs text-slate-400 font-medium">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $roleClasses = match ($user->role->value) {
                                                    'super-admin' => 'bg-slate-900 text-white',
                                                    'admin' => 'bg-primary text-white',
                                                    'gestor' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'organizador' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                    default => 'bg-slate-50 text-slate-500 border-slate-200'
                                                };
                                                $roleLabel = match ($user->role->value) {
                                                    'super-admin' => 'Super Admin',
                                                    'admin' => 'Administrador',
                                                    'gestor' => 'Gestor de Vendas',
                                                    'organizador' => 'Organizador de Prova',
                                                    default => ucfirst($user->role->value)
                                                };
                                            @endphp
                        <span
                                                class="px-2.5 py-1 border border-transparent {{ $roleClasses }} text-[10px] font-extrabold uppercase rounded-full tracking-wider">
                                                {{ $roleLabel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-xs font-bold text-slate-700">{{ $user->created_at->format('d/m/Y') }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium">{{ $user->created_at->format('H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="size-8 rounded-lg border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 transition-all"
                                                    title="Editar permissões">
                                                    <span class="material-symbols-outlined text-lg">edit</span>
                                                </a>

                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                        onsubmit="return confirm('Tem certeza que deseja remover este administrador?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="size-8 rounded-lg border border-slate-100 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-100 transition-all"
                                                            title="Excluir usuário">
                                                            <span class="material-symbols-outlined text-lg">delete</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-slate-200 text-6xl mb-4">shield_person</span>
                                <p class="text-slate-400 font-medium">Nenhum administrador encontrado.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection