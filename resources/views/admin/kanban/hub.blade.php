@extends('layouts.admin')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-primary">Operações</span>
                <span class="text-slate-300">/</span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Hub de Produção</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter leading-none">
                Command <span class="text-primary text-4xl leading-none">Center</span>
            </h2>
            <p class="text-slate-500 text-sm font-medium mt-2">Visão consolidada da produção de <span class="font-bold text-slate-700 italic">{{ $events->count() }}</span> eventos ativos.</p>
        </div>
        
        <div class="flex bg-white px-6 py-4 rounded-3xl shadow-sm border border-slate-100 divide-x divide-slate-100">
            <div class="pr-6">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Taxa de Conclusão</p>
                <div class="flex items-end gap-2">
                    <span class="text-2xl font-black text-slate-800 italic leading-none">{{ $stats['completion_rate'] }}%</span>
                    <span class="text-[10px] font-bold text-green-500 mb-0.5">Global</span>
                </div>
            </div>
            <div class="pl-6">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Tasks</p>
                <span class="text-2xl font-black text-slate-800 italic leading-none">{{ $stats['total_tasks'] }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
        <!-- Analytics Section -->
        <div class="lg:col-span-8 bg-white p-8 rounded-[32px] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-10">
            <div class="w-full md:w-1/2 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase italic tracking-tighter mb-1">Distribuição de Status</h3>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-6">Progresso total da equipe</p>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Concluídas</span>
                        <span class="text-sm font-black text-green-500 italic">{{ $stats['done_tasks'] }}</span>
                    </div>
                    <div class="w-full bg-slate-50 h-2 rounded-full overflow-hidden">
                        <div class="bg-green-500 h-full rounded-full" style="width: {{ $stats['completion_rate'] }}%"></div>
                    </div>
                    
                    <div class="flex justify-between items-end pt-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Pendentes</span>
                        <span class="text-sm font-black text-orange-500 italic">{{ $stats['pending_tasks'] }}</span>
                    </div>
                    <div class="w-full bg-slate-50 h-2 rounded-full overflow-hidden">
                        <div class="bg-orange-500 h-full rounded-full" style="width: {{ 100 - $stats['completion_rate'] }}%"></div>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-50 flex gap-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Média p/ Evento</p>
                        <p class="text-lg font-black text-slate-700 italic">{{ $events->count() > 0 ? round($stats['total_tasks'] / $events->count(), 1) : 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Alertas Críticos</p>
                        <p class="text-lg font-black text-red-500 italic">{{ $stats['high_priority'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="w-full md:w-1/2 flex flex-col items-center justify-center relative">
                <div class="size-48">
                    <canvas id="priorityChart"></canvas>
                </div>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-4">
                    <span class="text-2xl font-black text-slate-800 italic leading-none">{{ $stats['total_tasks'] }}</span>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">TASKS</span>
                </div>
            </div>
        </div>

        <!-- Priority Breakdown -->
        <div class="lg:col-span-4 bg-slate-900 p-8 rounded-[32px] shadow-2xl shadow-primary/10 text-white flex flex-col overflow-hidden relative">
            <div class="size-64 bg-primary/20 rounded-full blur-3xl absolute -top-32 -right-32 pointer-events-none"></div>
            <div class="relative z-10 h-full flex flex-col">
                <h3 class="text-lg font-black uppercase italic tracking-tighter mb-6">Breakdown de Prioridade</h3>
                
                <div class="flex-grow space-y-6">
                    <div class="flex items-center gap-4 group">
                        <div class="size-12 rounded-2xl bg-red-500/20 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">priority_high</span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Urgente</span>
                                <span class="text-sm font-black text-white italic">{{ $stats['priority_dist']['high'] }}</span>
                            </div>
                            <div class="w-full bg-white/5 h-1 rounded-full overflow-hidden">
                                <div class="bg-red-500 h-full" style="width: {{ $stats['total_tasks'] > 0 ? ($stats['priority_dist']['high'] / $stats['total_tasks']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 group">
                        <div class="size-12 rounded-2xl bg-orange-500/20 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">bolt</span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Intermediário</span>
                                <span class="text-sm font-black text-white italic">{{ $stats['priority_dist']['medium'] }}</span>
                            </div>
                            <div class="w-full bg-white/5 h-1 rounded-full overflow-hidden">
                                <div class="bg-orange-500 h-full" style="width: {{ $stats['total_tasks'] > 0 ? ($stats['priority_dist']['medium'] / $stats['total_tasks']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 group">
                        <div class="size-12 rounded-2xl bg-blue-500/20 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">schedule</span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Baixa Prioridade</span>
                                <span class="text-sm font-black text-white italic">{{ $stats['priority_dist']['low'] }}</span>
                            </div>
                            <div class="w-full bg-white/5 h-1 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full" style="width: {{ $stats['total_tasks'] > 0 ? ($stats['priority_dist']['low'] / $stats['total_tasks']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-white/5 rounded-2xl border border-white/10 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-xl">tips_and_updates</span>
                    <p class="text-[10px] font-medium text-slate-400 leading-tight">Mantenha a prioridade baixa abaixo de 50% para um fluxo saudável.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Feed -->
    <div class="mb-6 flex justify-between items-end px-2">
        <div>
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest italic">Live Feed <span class="text-slate-200">/</span> Produção</h3>
        </div>
        <div class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center gap-2">
            <span class="size-1.5 rounded-full bg-primary animate-pulse"></span>
            Atualizado agora
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @foreach($events as $event)
            <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden group hover:border-primary/20 transition-all flex flex-col">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/20">
                    <div class="flex items-center gap-4">
                        <div class="size-14 rounded-2xl overflow-hidden shadow-md">
                            <img src="{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 uppercase italic tracking-tighter group-hover:text-primary transition-colors leading-tight">{{ $event->name }}</h3>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}
                                </span>
                                <span class="text-[9px] font-black text-primary uppercase tracking-widest flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">task_alt</span>
                                    {{ $event->kanbanColumns->flatMap->tasks->count() }} Tarefas
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.corridas.kanban', $event->id) }}" 
                        class="size-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm group-hover:scale-105">
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
                
                <div class="p-8 flex-grow">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                        @foreach($event->kanbanColumns as $column)
                            <div class="space-y-4">
                                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                                    <div class="flex items-center gap-1.5">
                                        <div class="size-1.5 rounded-full" style="background-color: {{ $column->color_hex }}"></div>
                                        <h4 class="text-[9px] font-black uppercase tracking-tighter text-slate-500 truncate max-w-[60px]">{{ $column->name }}</h4>
                                    </div>
                                    <span class="text-[9px] font-black text-slate-300 italic">{{ $column->tasks->count() }}</span>
                                </div>
                                <div class="space-y-2">
                                    @php $topTasks = $column->tasks->take(2); @endphp
                                    @foreach($topTasks as $task)
                                        <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-100/30 group/task hover:bg-white hover:border-primary/10 transition-all cursor-default relative">
                                            <p class="text-[9px] font-bold text-slate-700 leading-tight mb-1 truncate">{{ $task->title }}</p>
                                            <div class="flex justify-between items-center">
                                                <span class="px-1 py-0.5 rounded text-[7px] font-black uppercase {{ match($task->priority) { 'high' => 'bg-red-50 text-red-400', 'medium' => 'bg-orange-50 text-orange-400', default => 'bg-blue-50 text-blue-400' } }}">
                                                    {{ $task->priority }}
                                                </span>
                                                @if($task->assignee)
                                                    <div class="size-4 rounded-full bg-slate-200 flex items-center justify-center text-[7px] font-black text-slate-500">
                                                        {{ substr($task->assignee->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($column->tasks->count() > 2)
                                        <div class="text-center py-1">
                                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">+{{ $column->tasks->count() - 2 }} extra</span>
                                        </div>
                                    @endif
                                    @if($column->tasks->count() === 0)
                                        <div class="h-10 flex items-center justify-center">
                                            <span class="text-[8px] font-black text-slate-200 uppercase tracking-widest italic opacity-40">Clean</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Event Progress Bar -->
                @php 
                    $totalEvTasks = $event->kanbanColumns->flatMap->tasks->count();
                    $doneEvTasks = $event->kanbanColumns->where('name', 'Concluído')->flatMap->tasks->count();
                    $evPercent = $totalEvTasks > 0 ? ($doneEvTasks / $totalEvTasks) * 100 : 0;
                @endphp
                <div class="p-6 bg-slate-50/50 border-t border-slate-50 mt-auto flex items-center gap-6">
                    <div class="flex-grow">
                        <div class="flex justify-between text-[8px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            <span>Progresso do Evento</span>
                            <span class="text-slate-800 italic">{{ round($evPercent) }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 h-1 rounded-full overflow-hidden">
                            <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: {{ $evPercent }}%"></div>
                        </div>
                    </div>
                    <div class="flex -space-x-2">
                        @foreach($event->managers->take(3) as $manager)
                            <div class="size-7 rounded-full bg-white border-2 border-slate-50 flex items-center justify-center text-[8px] font-black text-slate-600 shadow-sm" title="{{ $manager->name }}">
                                {{ substr($manager->name, 0, 1) }}
                            </div>
                        @endforeach
                        @if($event->managers->count() > 3)
                            <div class="size-7 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[8px] font-black text-slate-400 shadow-sm">
                                +{{ $event->managers->count() - 3 }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        @if($events->isEmpty())
             <div class="col-span-full bg-white rounded-[40px] p-24 text-center border-2 border-dashed border-slate-100">
                <div class="size-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-4xl text-slate-200">inventory_2</span>
                </div>
                <h3 class="text-2xl font-black text-slate-300 uppercase italic tracking-tighter">Radar Silencioso</h3>
                <p class="text-slate-400 text-sm font-medium">Nenhuma operação de produção detectada no momento.</p>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('priorityChart').getContext('2d');
            const data = @json($stats['priority_dist']);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Alta', 'Média', 'Baixa'],
                    datasets: [{
                        data: [data.high, data.medium, data.low],
                        backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6'],
                        borderWidth: 0,
                        hoverOffset: 15,
                        spacing: 8,
                        borderRadius: 10
                    }]
                },
                options: {
                    cutout: '80%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 11 },
                            displayColors: true,
                            boxPadding: 6
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
