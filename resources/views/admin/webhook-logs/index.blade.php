@extends('layouts.admin')

@section('title', 'Logs de Webhook - Asaas')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">
                Logs de <span class="text-primary">Webhook</span>
            </h2>
            <p class="text-slate-500 text-sm font-medium">Monitoramento de eventos recebidos da API do Asaas.</p>
        </div>

        <div>
            <form action="{{ route('admin.webhook-logs.destroy-all') }}" method="POST"
                onsubmit="return confirm('ATENÇÃO: Tem certeza que deseja deletar TODOS os logs do banco de dados?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-50 text-red-600 px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-100 transition-all flex items-center gap-2 border border-red-100">
                    <span class="material-symbols-outlined text-sm">delete_sweep</span>
                    Limpar Todos
                </button>
            </form>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm mb-8">
        <form method="GET" action="{{ route('admin.webhook-logs.index') }}"
            class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 w-full">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">Evento
                    Asaas</label>
                <div class="relative">
                    <select name="event"
                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-primary/20 transition-all appearance-none cursor-pointer">
                        <option value="">Todos os eventos</option>
                        @foreach($events as $event)
                            <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                {{ $event }}
                            </option>
                        @endforeach
                    </select>
                    <span
                        class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>

            <div class="flex-1 w-full">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">Data dos
                    Eventos</label>
                <input type="date" name="date"
                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-primary/20 transition-all"
                    value="{{ request('date') }}">
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit"
                    class="flex-1 md:flex-none bg-slate-800 text-white px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-slate-200">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    Filtrar
                </button>
                <a href="{{ route('admin.webhook-logs.index') }}"
                    class="flex-1 md:flex-none bg-slate-100 text-slate-500 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">clear_all</span>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">ID
                        </th>
                        <th class="px-6 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Evento Webhook</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Payment ID</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Ordem vinculada</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Status HTTP</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Data/Hora</th>
                        <th class="px-6 py-5 text-right text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 text-xs font-black text-slate-400">#{{ $log->id }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                                    {{ $log->event }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-600 truncate max-w-[150px]">
                                {{ $log->payment_id ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->order_id)
                                    <a href="{{ route('admin.sales.show', $log->order_id) }}"
                                        class="text-xs font-black text-primary hover:underline flex items-center gap-1 w-fit">
                                        <span class="material-symbols-outlined text-[14px]">link</span>
                                        {{ $log->order->order_number ?? $log->order_id }}
                                    </a>
                                @else
                                    <span class="text-xs font-bold text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($log->status_code == 200)
                                    <span class="flex items-center gap-1 text-xs font-black text-emerald-500">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                        200 OK
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 text-xs font-black text-red-500">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $log->status_code }} Fail
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-400">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.webhook-logs.show', $log->id) }}"
                                        class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-blue-800 hover:underline transition-all">
                                        Explorar
                                    </a>

                                    <form action="{{ route('admin.webhook-logs.destroy', $log->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Tem certeza que deseja deletar permanentemente este log?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-700 hover:underline transition-all">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <span class="material-symbols-outlined text-4xl text-slate-200">webhook</span>
                                    <p class="text-sm font-bold text-slate-400">Ainda não há eventos webhook registrados.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-6 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
    </div>
@endsection