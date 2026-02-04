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
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-50 pb-4">
                        <div>
                            <h3 class="text-lg font-black uppercase italic tracking-tight">Provas Vinculadas</h3>
                            <p class="text-slate-500 text-xs font-medium italic">Selecione as corridas que este organizador poderá gerenciar.</p>
                        </div>
                        
                        <div class="relative min-w-[300px]">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                            <input type="text" id="event-search-input" placeholder="Buscar prova por nome, cidade ou estado..." 
                                class="w-full bg-slate-50 border-transparent rounded-xl pl-12 pr-4 py-3 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                        </div>
                    </div>

                    <div class="max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        <div id="events-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $selectedEvents = old('events', $user->managedEvents->pluck('id')->toArray());
                            @endphp
                            @foreach($events as $event)
                                <label class="event-selection-card flex items-center gap-4 p-3 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-primary/30 transition-all cursor-pointer group relative overflow-hidden" 
                                    data-name="{{ strtolower($event->name) }}" 
                                    data-location="{{ strtolower($event->city . ' ' . $event->state) }}">
                                    
                                    <div class="relative size-16 rounded-xl overflow-hidden flex-shrink-0 shadow-sm">
                                        <img src="{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}" 
                                            class="absolute inset-0 size-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                                    </div>

                                    <div class="flex flex-col flex-grow min-w-0">
                                        <span class="text-sm font-bold text-slate-800 truncate uppercase italic tracking-tight group-hover:text-primary transition-colors">{{ $event->name }}</span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="material-symbols-outlined text-[10px] text-primary">calendar_today</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $event->event_date->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[10px] text-slate-300">location_on</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter truncate">{{ $event->city }}/{{ $event->state }}</span>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0 pr-2">
                                        <input type="checkbox" name="events[]" value="{{ $event->id }}" 
                                            class="size-5 rounded-lg border-slate-300 text-primary focus:ring-primary transition-all cursor-pointer"
                                            {{ in_array($event->id, $selectedEvents) ? 'checked' : '' }}>
                                    </div>

                                    {{-- Highlight border when checked --}}
                                    <div class="absolute inset-0 border-2 border-primary rounded-2xl pointer-events-none opacity-0 transition-opacity {{ in_array($event->id, $selectedEvents) ? 'opacity-100' : '' }} checked-border"></div>
                                </label>
                            @endforeach
                        </div>
                        
                        <div id="no-events-found" class="hidden py-20 text-center">
                            <span class="material-symbols-outlined text-slate-200 text-5xl mb-3">search_off</span>
                            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhuma corrida encontrada</p>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Role Toggle
                document.querySelector('select[name="role"]').addEventListener('change', function() {
                    const section = document.getElementById('events-selection-section');
                    if (this.value === 'organizador') {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });

                // Search Filter
                const searchInput = document.getElementById('event-search-input');
                const eventCards = document.querySelectorAll('.event-selection-card');
                const noEventsMessage = document.getElementById('no-events-found');

                searchInput.addEventListener('input', function() {
                    const term = this.value.toLowerCase().trim();
                    let hasResults = false;

                    eventCards.forEach(card => {
                        const name = card.dataset.name;
                        const location = card.dataset.location;
                        
                        if (name.includes(term) || location.includes(term)) {
                            card.classList.remove('hidden');
                            hasResults = true;
                        } else {
                            card.classList.add('hidden');
                        }
                    });

                    if (hasResults) {
                        noEventsMessage.classList.add('hidden');
                    } else {
                        noEventsMessage.classList.remove('hidden');
                    }
                });

                // Dynamic Border on Check
                eventCards.forEach(card => {
                    const checkbox = card.querySelector('input[type="checkbox"]');
                    const border = card.querySelector('.checked-border');
                    
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            border.classList.remove('opacity-0');
                            border.classList.add('opacity-100');
                            card.classList.add('bg-blue-50/30');
                        } else {
                            border.classList.add('opacity-0');
                            border.classList.remove('opacity-100');
                            card.classList.remove('bg-blue-50/30');
                        }
                    });

                    // Set initial background if checked
                    if (checkbox.checked) {
                        card.classList.add('bg-blue-50/30');
                    }
                });
            </script>

            <style>
                .custom-scrollbar::-webkit-scrollbar {
                    width: 6px;
                }
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #e2e8f0;
                    border-radius: 10px;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #cbd5e1;
                }
            </style>
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
