@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">
            Hub de <span class="text-primary">Produção Geral</span>
        </h2>
        <p class="text-slate-500 text-sm font-medium">Visão consolidada de todas as tarefas e corridas.</p>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-2xl">assignment</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total de Tarefas</p>
                <p class="text-2xl font-black text-slate-800 italic">{{ $stats['total_tasks'] }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-600">
                <span class="material-symbols-outlined text-2xl">pending_actions</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pendentes</p>
                <p class="text-2xl font-black text-slate-800 italic">{{ $stats['pending_tasks'] }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-600">
                <span class="material-symbols-outlined text-2xl">priority_high</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Prioridade Alta</p>
                <p class="text-2xl font-black text-slate-800 italic">{{ $stats['high_priority'] }}</p>
            </div>
        </div>
    </div>

    <!-- Events List -->
    <div class="space-y-8">
        @foreach($events as $event)
            <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 uppercase italic tracking-tighter">{{ $event->name }}</h3>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Resumo de produção do evento</p>
                    </div>
                    <a href="{{ route('admin.corridas.kanban', $event->id) }}" 
                        class="bg-white border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white hover:border-primary transition-all flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-lg">view_kanban</span>
                        Abrir Kanban Full
                    </a>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        @foreach($event->kanbanColumns as $column)
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 border-b border-slate-50 pb-3">
                                    <div class="size-2 rounded-full" style="background-color: {{ $column->color_hex }}"></div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-500">{{ $column->name }}</h4>
                                    <span class="ml-auto text-[10px] font-black text-slate-300">{{ $column->tasks->count() }}</span>
                                </div>
                                <div class="space-y-2">
                                    @foreach($column->tasks->take(3) as $task)
                                        <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100/50">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-[8px] font-black uppercase px-1.5 py-0.5 rounded {{ match($task->priority) { 'high' => 'bg-red-50 text-red-500', 'medium' => 'bg-amber-50 text-amber-500', default => 'bg-blue-50 text-blue-500' } }}">
                                                    {{ $task->priority }}
                                                </span>
                                            </div>
                                            <p class="text-[10px] font-bold text-slate-700 leading-tight mb-1 truncate">{{ $task->title }}</p>
                                            @if($task->assignee)
                                                <div class="flex items-center gap-1.5 grayscale opacity-50">
                                                    <span class="material-symbols-outlined text-[10px]">person</span>
                                                    <span class="text-[8px] font-bold text-slate-400 uppercase truncate">{{ $task->assignee->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($column->tasks->count() > 3)
                                        <p class="text-center text-[9px] font-black text-slate-300 uppercase tracking-widest mt-2">+ {{ $column->tasks->count() - 3 }} tarefas</p>
                                    @endif
                                    @if($column->tasks->count() === 0)
                                        <p class="text-center py-4 text-[9px] font-black text-slate-200 uppercase tracking-widest italic">Vazio</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        @if($events->isEmpty())
            <div class="bg-white rounded-[32px] p-20 border border-dashed border-slate-200 text-center">
                <span class="material-symbols-outlined text-6xl text-slate-200 mb-4">inventory_2</span>
                <h3 class="text-xl font-black text-slate-400 uppercase italic tracking-tighter">Nenhuma corrida vinculada</h3>
                <p class="text-slate-400 text-sm font-medium">Você ainda não possui corridas atribuídas para gerenciar produção.</p>
            </div>
        @endif
    </div>
@endsection
