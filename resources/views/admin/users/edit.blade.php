@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="size-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Editar <span class="text-primary">Administrador</span></h2>
                <p class="text-slate-500 text-sm font-medium">Atualize as permissões ou dados de {{ $user->name }}.</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Informações Básicas -->
                <div class="md:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">Dados de Acesso</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome Completo</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                placeholder="Ex: João Silva">
                            @error('name') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Email Profissional</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                placeholder="email@empresa.com">
                            @error('email') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cargo / Role</label>
                            <select name="role" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none cursor-pointer">
                                @foreach(\App\Enums\UserRole::cases() as $role)
                                    @if($role !== \App\Enums\UserRole::Client)
                                        <option value="{{ $role->value }}" {{ old('role', $user->role->value) === $role->value ? 'selected' : '' }}>
                                            @switch($role->value)
                                                @case('super-admin') Super Admin (Acesso Total) @break
                                                @case('admin') Administrador Padrão @break
                                                @case('gestor') Gestor (Financeiro/Vendas) @break
                                                @case('organizador') Organizador (Apenas Provas) @break
                                            @endswitch
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('role') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">ID do Usuário</label>
                            <div class="flex items-center h-[52px]">
                                <span class="bg-slate-100 text-slate-500 text-[10px] font-black px-4 py-2 rounded-lg border border-slate-200 uppercase tracking-widest">
                                    #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Segurança -->
                <div class="md:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">Alterar Senha</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nova Senha</label>
                            <input type="password" name="password" 
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                placeholder="Deixe em branco para manter">
                            @error('password') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Confirmar Nova Senha</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                placeholder="Confirme a nova senha">
                        </div>
                    </div>
                </div>

                <!-- Provas Vinculadas (Apenas para Organizador) -->
                <div id="events-selection-section" class="md:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-100 {{ old('role', $user->role->value) === 'organizador' ? '' : 'hidden' }}">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">Provas Vinculadas</h3>
                    <p class="text-slate-500 text-xs font-medium mb-6 italic">Selecione as corridas que este organizador poderá gerenciar.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $selectedEvents = old('events', $user->managedEvents->pluck('id')->toArray());
                        @endphp
                        @foreach($events as $event)
                            <label class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-transparent hover:border-primary transition-all cursor-pointer group">
                                <input type="checkbox" name="events[]" value="{{ $event->id }}" 
                                    class="size-5 rounded border-slate-300 text-primary focus:ring-primary transition-all"
                                    {{ in_array($event->id, $selectedEvents) ? 'checked' : '' }}>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700 group-hover:text-primary transition-colors uppercase italic tracking-tight">{{ $event->name }}</span>
                                    <span class="text-[10px] font-medium text-slate-400">{{ $event->event_date->format('d/m/Y') }} • {{ $event->city }}/{{ $event->state }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <script>
                document.querySelector('select[name="role"]').addEventListener('change', function() {
                    const section = document.getElementById('events-selection-section');
                    if (this.value === 'organizador') {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });
            </script>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.users.index') }}"
                    class="bg-white border border-slate-200 text-slate-600 px-8 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                    Cancelar
                </a>
                <button type="submit"
                    class="bg-slate-900 text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-slate-900/20">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
@endsection
