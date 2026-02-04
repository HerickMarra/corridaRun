@extends('layouts.admin')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.corridas.index') }}" class="text-slate-400 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                </a>
                <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">
                    Dashboard: <span class="text-primary">{{ $event->name }}</span>
                </h2>
            </div>
            <p class="text-slate-500 text-sm font-medium ml-8">Análise detalhada de performance e inscrições.</p>
        </div>
        <div class="flex gap-3 ml-8 md:ml-0">
            <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                class="bg-white border border-slate-200 text-slate-600 px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary hover:text-white hover:border-primary transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">visibility</span>
                Página Pública
            </a>
            <a href="{{ route('admin.corridas.edit', $event->id) }}"
                class="bg-white border border-slate-200 text-slate-600 px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">edit</span>
                Editar Corrida
            </a>
            <a href="{{ route('admin.corridas.kanban', $event->id) }}"
                class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">view_kanban</span>
                Gestão de Tarefas
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-2xl">payments</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 text-nowrap">Receita em Bruto</p>
                    <p class="text-2xl font-black text-slate-800 italic">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Taxas estimadas</span>
                <span class="text-xs font-black text-slate-600">R$ {{ number_format($serviceFee, 2, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-12 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-600">
                    <span class="material-symbols-outlined text-2xl">group</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 text-nowrap">Total de Inscritos</p>
                    <p class="text-2xl font-black text-slate-800 italic">{{ $totalInscriptions }} / {{ $event->max_participants }}</p>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-orange-500 h-full rounded-full" style="width: {{ $event->max_participants > 0 ? min(100, ($totalInscriptions / $event->max_participants) * 100) : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-12 rounded-2xl bg-green-500/10 flex items-center justify-center text-green-600">
                    <span class="material-symbols-outlined text-2xl">shopping_cart</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 text-nowrap">Ticket Médio</p>
                    <p class="text-2xl font-black text-slate-800 italic">R$ {{ number_format($avgTicket, 2, ',', '.') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Eficiência</span>
                <span class="text-xs font-black text-green-600">Alta</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined text-2xl">event</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 text-nowrap">Dias Restantes</p>
                    @php 
                        $diff = (int) now()->diffInDays(\Carbon\Carbon::parse($event->event_date), false);
                    @endphp
                    <p class="text-2xl font-black text-slate-800 italic">{{ $diff > 0 ? $diff : 'Evento Realizado' }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</span>
                <span class="text-xs font-black text-blue-600 uppercase">{{ $event->status }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Main Chart -->
        <div class="lg:col-span-8 bg-white p-8 rounded-[32px] shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase italic tracking-tighter">Fluxo de Inscrições</h3>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Tendência histórica de performance</p>
                </div>
                <div class="flex gap-2">
                    <span class="flex items-center gap-2 text-[10px] font-black uppercase text-slate-400">
                        <span class="size-2 rounded-full bg-primary"></span> Volume
                    </span>
                </div>
            </div>
            <div class="h-[350px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Categories Progress -->
        <div class="lg:col-span-4 bg-white p-8 rounded-[32px] shadow-sm border border-slate-100">
            <h3 class="text-lg font-black text-slate-800 uppercase italic tracking-tighter mb-8">Inscritos por Categoria</h3>
            <div class="space-y-8">
                @foreach($categoryStats as $stat)
                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <div>
                                <h4 class="text-sm font-black text-slate-700 uppercase italic leading-tight">{{ $stat['name'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    {{ $stat['sold'] }} de {{ $stat['max'] }} vagas
                                </p>
                            </div>
                            <span class="text-sm font-black text-primary italic">{{ round($stat['percent']) }}%</span>
                        </div>
                        <div class="w-full bg-slate-50 h-3 rounded-full overflow-hidden border border-slate-100">
                            <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: {{ $stat['percent'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 p-6 rounded-2xl bg-slate-50/50 border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Resumo Financeiro</p>
                <div class="space-y-3">
                    @foreach($categoryStats as $stat)
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-600 truncate mr-4">{{ $stat['name'] }}</span>
                            <span class="text-xs font-black text-slate-800">R$ {{ number_format($stat['revenue'], 2, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Inscriptions -->
    <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-black text-slate-800 uppercase italic tracking-tighter">Últimas Inscrições Confirmadas</h3>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Os 10 atletas mais recentes</p>
            </div>
            <a href="{{ route('admin.athletes.index') }}?event={{ $event->id }}" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Atleta</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">CPF</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Categoria</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Preço</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data/Hora</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentInscriptions as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-[10px]">
                                        {{ substr($item->participant_name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $item->participant_name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-xs font-medium text-slate-500">{{ $item->participant_cpf }}</td>
                            <td class="px-8 py-4">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">
                                    {{ $item->category->name }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-sm font-black text-slate-800 italic">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                            <td class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase">
                                {{ $item->created_at->format('d/m/y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium">
                                Nenhuma inscrição confirmada ainda para este evento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesData = @json($salesByDay);
            
            const labels = salesData.map(d => {
                const date = new Date(d.date + 'T00:00:00');
                return date.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short' });
            });
            const values = salesData.map(d => d.count);
            const revenues = salesData.map(d => d.revenue);

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, '#0d59f233');
            gradient.addColorStop(1, '#0d59f200');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Inscrições',
                        data: values,
                        borderColor: '#0d59f2',
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0d59f2',
                        pointBorderWidth: 2,
                        pointRadius: values.length > 31 ? 2 : 4,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradient
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Inter', weight: 'bold' },
                            bodyFont: { family: 'Inter' },
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    return [
                                        `Inscrições: ${context.parsed.y}`,
                                        `Receita: R$ ${Number(revenues[index]).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { 
                                font: { family: 'Inter', weight: 'bold', size: 10 }, 
                                color: '#94a3b8',
                                precision: 0
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                font: { family: 'Inter', weight: 'bold', size: 10 }, 
                                color: '#94a3b8',
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 10
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
