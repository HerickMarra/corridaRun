@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Gestão de <span
                    class="text-primary">Vendas</span></h2>
            <p class="text-slate-500 text-sm font-medium">Acompanhe o desempenho financeiro e gerencie os pedidos.</p>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-10 rounded-2xl bg-primary/5 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Faturamento Total</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800 italic">R$
                {{ number_format($stats['total_revenue'], 2, ',', '.') }}
            </h3>
            <p class="text-[9px] text-green-500 font-bold uppercase mt-2">Pedidos Pagos: {{ $stats['paid_orders_count'] }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-10 rounded-2xl bg-orange-500/5 flex items-center justify-center text-orange-500">
                    <span class="material-symbols-outlined">shopping_basket</span>
                </div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Total de Pedidos</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800 italic">{{ $stats['orders_count'] }}</h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase mt-2">Incluindo pendentes/cancelados</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-10 rounded-2xl bg-emerald-500/5 flex items-center justify-center text-emerald-500">
                    <span class="material-symbols-outlined">confirmation_number</span>
                </div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Ticket Médio</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800 italic">R$
                {{ number_format($stats['average_ticket'], 2, ',', '.') }}
            </h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase mt-2">Baseado em pedidos pagos</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-10 rounded-2xl bg-blue-500/5 flex items-center justify-center text-blue-500">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Conversão</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800 italic">
                {{ $stats['orders_count'] > 0 ? number_format(($stats['paid_orders_count'] / $stats['orders_count']) * 100, 1) : 0 }}%
            </h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase mt-2">Pedidos pagos vs totais</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-sm font-black uppercase italic tracking-tight text-slate-800">Evolução de Faturamento</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Últimos 30 dias</span>
            </div>
            <div class="h-[300px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm">
            <h3 class="text-sm font-black uppercase italic tracking-tight text-slate-800 mb-6">Filtros Avançados</h3>
            <form action="{{ route('admin.sales.index') }}" method="GET" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Evento</label>
                    <select name="event_id"
                        class="w-full bg-slate-50 border-transparent rounded-xl px-4 py-3 text-xs font-bold focus:bg-white transition-all">
                        <option value="">Todos os Eventos</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Status</label>
                    <select name="status"
                        class="w-full bg-slate-50 border-transparent rounded-xl px-4 py-3 text-xs font-bold focus:bg-white transition-all">
                        <option value="">Todos os Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ ucfirst($status->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Início</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full bg-slate-50 border-transparent rounded-xl px-4 py-3 text-xs font-bold focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Fim</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full bg-slate-50 border-transparent rounded-xl px-4 py-3 text-xs font-bold focus:bg-white transition-all">
                    </div>
                </div>

                <div class="pt-4 flex gap-2">
                    <button type="submit"
                        class="flex-grow bg-primary text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all">Filtrar</button>
                    <a href="{{ route('admin.sales.index') }}"
                        class="bg-slate-100 text-slate-500 p-3 rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm">restart_alt</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-sm font-black uppercase italic tracking-tight text-slate-800">Detalhamento de Pedidos</h3>
            <form action="{{ route('admin.sales.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por pedido ou cliente..."
                    class="bg-white border-slate-200 rounded-full pl-10 pr-4 py-2 text-xs font-medium w-64 focus:ring-primary focus:border-primary transition-all">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-4 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">#
                            Pedido</th>
                        <th class="px-4 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Cliente</th>
                        <th class="px-4 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Evento</th>
                        <th class="px-4 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Valor</th>
                        <th class="px-4 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Método</th>
                        <th class="px-4 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Status</th>
                        <th class="px-4 py-5 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Data</th>
                        <th class="px-4 py-5 text-right text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-4 py-6">
                                <span class="text-xs font-black text-slate-800">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="size-8 bg-slate-100 rounded-full flex items-center justify-center text-[10px] font-black text-slate-500">
                                        {{ substr($order->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-700">{{ $order->user->name }}</p>
                                        <p class="text-[9px] font-medium text-slate-400">{{ $order->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6">
                                @foreach($order->items as $item)
                                    <p class="text-xs font-bold text-slate-600 truncate max-w-[200px]">
                                        {{ $item->category->event->name ?? 'Evento não encontrado' }}
                                    </p>
                                    <p class="text-[9px] font-medium text-slate-400 italic">
                                        {{ $item->category->name ?? 'Categoria não encontrada' }}</p>
                                @endforeach
                            </td>
                            <td class="px-4 py-6">
                                <span class="text-xs font-black text-slate-800">R$
                                    {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <span
                                    class="px-3 py-1 bg-slate-100 rounded-full text-[9px] font-black uppercase tracking-widest text-slate-500">
                                    {{ $order->payment_method ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-6">
                                @php
                                    $statusClasses = [
                                        'paid' => 'bg-green-100 text-green-600',
                                        'pending' => 'bg-orange-100 text-orange-600',
                                        'cancelled' => 'bg-red-100 text-red-600',
                                        'refunded' => 'bg-blue-100 text-blue-600',
                                    ];
                                    $class = $statusClasses[$order->status->value] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span
                                    class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $class }}">
                                    {{ $order->status->value }}
                                </span>
                            </td>
                            <td class="px-4 py-6 text-xs font-bold text-slate-400">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.sales.show', $order->id) }}" class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-blue-800 hover:underline transition-all">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <span
                                        class="material-symbols-outlined text-4xl text-slate-200">sentiment_dissatisfied</span>
                                    <p class="text-sm font-bold text-slate-400">Nenhum pedido encontrado com estes filtros.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->count() > 0)
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('salesChart').getContext('2d');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#0f172a',
                            titleFont: { size: 13, family: 'Space Grotesk', weight: 'bold' },
                            bodyFont: { size: 12, family: 'Space Grotesk' },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return 'Receita: R$ ' + context.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: { family: 'Space Grotesk', size: 10, weight: 'bold' },
                                color: '#94a3b8',
                                callback: function (value) {
                                    return 'R$ ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { family: 'Space Grotesk', size: 10, weight: 'bold' },
                                color: '#94a3b8'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection