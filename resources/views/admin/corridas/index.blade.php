@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Gestão de <span
                    class="text-primary">Corridas</span></h2>
            <p class="text-slate-500 text-sm font-medium">Visualize e gerencie todas as provas cadastradas.</p>
        </div>
        <a href="{{ route('admin.corridas.create') }}"
            class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-lg">add</span>
            Nova Corrida
        </a>
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
@endsection