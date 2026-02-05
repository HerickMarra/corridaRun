@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Gestão de <span
                    class="text-primary">Corridas</span></h2>
            <p class="text-slate-500 text-sm font-medium">Visualize e gerencie todas as provas cadastradas.</p>
        </div>
        @if(auth()->user()->role->isAdmin() && in_array(auth()->user()->role->value, ['super-admin', 'admin']))
            <a href="{{ route('admin.corridas.create') }}"
                class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">add</span>
                Nova Corrida
            </a>
        @endif
    </div>

    <!-- Filtros e Busca -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <form method="GET" action="{{ route('admin.corridas.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Busca por nome -->
            <div class="flex-1">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Buscar por
                    nome</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Digite o nome da corrida..."
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <!-- Filtro por status -->
            <div class="md:w-64">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Status</label>
                <select name="status"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">Todos os status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Rascunho</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Encerrado</option>
                </select>
            </div>

            <!-- Botões -->
            <div class="flex gap-2 md:self-end">
                <button type="submit"
                    class="bg-primary text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all">
                    Filtrar
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.corridas.index') }}"
                        class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Limpar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Corrida</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Localização
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Categorias
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($events as $event)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                                        <img src="{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}"
                                            class="w-full h-full object-cover" alt="{{ $event->name }}">
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 leading-none mb-1">{{ $event->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ID:
                                            #{{ str_pad($event->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-700">{{ $event->event_date->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">Às 06:00 AM</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                {{ $event->city }}, {{ $event->state }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-primary text-[10px] font-black px-2.5 py-1 rounded-full uppercase">
                                    {{ $event->categories_count }} Categorias
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = match ($event->status->value) {
                                        'published' => 'bg-green-50 text-green-600 border-green-100',
                                        'draft' => 'bg-slate-50 text-slate-500 border-slate-200',
                                        'closed' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                        default => 'bg-slate-50 text-slate-500 border-slate-200'
                                    };
                                    $statusLabel = match ($event->status->value) {
                                        'published' => 'Publicado',
                                        'draft' => 'Rascunho',
                                        'closed' => 'Encerrado',
                                        'cancelled' => 'Cancelado',
                                        default => $event->status->value
                                    };
                                @endphp
                                <span
                                    class="px-2.5 py-1 border {{ $statusClasses }} text-[10px] font-extrabold uppercase rounded-full tracking-wider">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                                        class="size-8 rounded-lg border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 transition-all"
                                        title="Ver página pública">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.corridas.dashboard', $event->id) }}"
                                        class="size-8 rounded-lg border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 transition-all"
                                        title="Painel de Vendas">
                                        <span class="material-symbols-outlined text-lg">monitoring</span>
                                    </a>
                                    @if(auth()->user()->role->isAdmin() && in_array(auth()->user()->role->value, ['super-admin', 'admin']))
                                        <a href="{{ route('admin.corridas.edit', $event->id) }}"
                                            class="size-8 rounded-lg border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 transition-all">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <form action="{{ route('admin.corridas.destroy', $event->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja mover esta corrida para a lixeira?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="size-8 rounded-lg border border-slate-100 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-100 transition-all">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-slate-200 text-6xl mb-4">directions_run</span>
                                <p class="text-slate-400 font-medium">Nenhuma corrida cadastrada ainda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginação -->
    @if($events->hasPages())
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @endif
@endsection