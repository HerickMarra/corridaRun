@extends('layouts.admin')

@section('title', 'Detalhes do Webhook Log')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.webhook-logs.index') }}"
                    class="size-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-all">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">
                    Detalhes do <span class="text-primary">Webhook</span> #{{ $log->id }}
                </h2>
            </div>
            <p class="text-slate-500 text-sm font-medium ml-13">Visualização completa do disparo recebido.</p>
        </div>

        <div>
            <form action="{{ route('admin.webhook-logs.destroy', $log->id) }}" method="POST"
                onsubmit="return confirm('ATENÇÃO: Tem certeza que deseja deletar este log permanentemente?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-50 text-red-600 px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-100 transition-all flex items-center gap-2 border border-red-100">
                    <span class="material-symbols-outlined text-sm">delete</span>
                    Descartar Registro
                </button>
            </form>
        </div>
    </div>

    <!-- Grid info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Event Details -->
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                Dados da Transmissão
            </h3>

            <div class="space-y-4">
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">ID de Rastreio</span>
                    <span class="text-sm font-black text-slate-700">#{{ $log->id }}</span>
                </div>

                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Evento Asaas</span>
                    <div>
                        <span
                            class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                            {{ $log->event }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">ID de Cobrança
                        (Asaas_ID)</span>
                    <span class="text-sm font-black text-slate-700">{{ $log->payment_id ?? '-' }}</span>
                </div>

                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Status HTTPS da
                        Resposta</span>
                    <div>
                        @if($log->status_code == 200)
                            <span
                                class="inline-flex items-center gap-1 text-xs font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                200 OK
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1 text-xs font-black text-red-500 bg-red-50 px-2 py-1 rounded-md">
                                <span class="material-symbols-outlined text-[14px]">error</span>
                                {{ $log->status_code }} Falha
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Processado em</span>
                        <span
                            class="text-xs font-bold text-slate-600">{{ $log->processed_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Chegou em</span>
                        <span class="text-xs font-bold text-slate-600">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($log->order)
            <!-- Found Order matching details -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <!-- Visual detail -->
                <div class="absolute right-0 top-0 w-32 h-32 bg-primary/5 rounded-bl-[100px] -z-0"></div>

                <h3
                    class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6 flex items-center gap-2 relative z-10">
                    <span class="material-symbols-outlined text-primary">shopping_bag</span>
                    Compra Relacionada
                </h3>

                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Numeração</span>
                            <span class="text-sm font-black text-slate-700">#{{ $log->order->order_number }}</span>
                        </div>
                        <a href="{{ route('admin.sales.show', $log->order->id) }}"
                            class="text-xs font-black uppercase text-primary border border-primary/20 px-3 py-1.5 rounded-lg hover:bg-primary/5 transition-colors">
                            Inspecionar
                        </a>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Corredor Pagador</span>
                        <span class="text-sm font-bold text-slate-700">{{ $log->order->user->name }}</span>
                        <span class="text-[10px] text-slate-400">{{ $log->order->user->email }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Status no BD</span>
                            <div>
                                <span
                                    class="px-3 py-1 bg-{{ $log->order->status->color() }}-100 text-{{ $log->order->status->color() }}-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                                    {{ $log->order->status->label() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col items-end">
                            <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Total</span>
                            <span class="text-lg font-black text-slate-700">R$
                                {{ number_format($log->order->total_amount, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No matching order -->
            <div
                class="bg-slate-50 rounded-3xl p-8 border border-dashed border-slate-200 flex flex-col justify-center items-center text-center">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-4">link_off</span>
                <p class="text-sm font-bold text-slate-500 mb-1">Nenhuma Inscrição Vinculada.</p>
                <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 max-w-xs">
                    Este webhook não pôde ser mapeado para uma 'Order' existente no banco (ou a transação foi excluída).
                </p>
            </div>
        @endif
    </div>

    <!-- Payload JSON Dump -->
    <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-xl mb-8">
        <div class="bg-slate-800 px-6 py-4 flex items-center justify-between border-b border-slate-700/50">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-300 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-sm">data_object</span>
                Payload Recebido
            </h3>
            <span class="text-[10px] font-bold text-slate-500 font-mono">application/json</span>
        </div>
        <div class="p-6">
            <pre
                class="text-sm text-emerald-400 font-mono tracking-tight leading-relaxed overflow-x-auto"><code>{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code></pre>
        </div>
    </div>
@endsection