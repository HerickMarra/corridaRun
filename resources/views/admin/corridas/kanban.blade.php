@extends('layouts.admin')

@section('content')
    <div class="flex flex-col h-[calc(100vh-140px)]">
        <!-- Header do Kanban -->
        <div class="flex justify-between items-end mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.corridas.dashboard', $event->id) }}" class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-black transition-colors">Dashboard</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kanban de Tarefas</span>
                </div>
                <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">
                    Gestão de <span class="text-primary">Produção</span>
                </h2>
                <p class="text-slate-500 text-sm font-medium">Corrida: <span class="font-bold text-slate-700 italic">{{ $event->name }}</span></p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="openCreateColumnModal()" 
                    class="bg-white border border-slate-200 text-slate-600 px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">view_column</span>
                    Nova Coluna
                </button>
                <button onclick="openCreateTaskModal()" 
                    class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-lg">add_task</span>
                    Nova Tarefa
                </button>
            </div>
        </div>

        <!-- Board -->
        <div class="flex-grow overflow-x-auto pb-4 custom-scrollbar">
            <div id="kanban-board" class="flex gap-6 h-full min-w-max px-1">
                @foreach($event->kanbanColumns as $column)
                    <div class="kanban-column-wrapper w-80 flex flex-col h-full bg-slate-50/50 rounded-2xl border border-slate-100/50 p-4" data-column-id="{{ $column->id }}">
                        <!-- Coluna Header -->
                        <div class="group flex justify-between items-center mb-4 px-1 cursor-move">
                            <div class="flex items-center gap-2">
                                <div class="size-2.5 rounded-full" style="background-color: {{ $column->color_hex }}"></div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-slate-700">{{ $column->name }}</h3>
                                <span class="bg-white border border-slate-100 text-[9px] font-black px-1.5 py-0.5 rounded-md text-slate-400">
                                    {{ $column->tasks->count() }}
                                </span>
                            </div>
                            <button onclick="deleteColumn({{ $column->id }}, '{{ $column->name }}', {{ $column->tasks->count() }})" 
                                class="opacity-0 group-hover:opacity-100 transition-opacity text-slate-300 hover:text-red-500 p-1 rounded hover:bg-red-50"
                                title="Excluir coluna">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>

                        <!-- Tasks Container -->
                        <div class="flex-grow overflow-y-auto space-y-3 kanban-column custom-scrollbar pr-1" data-column-id="{{ $column->id }}">
                            @foreach($column->tasks as $task)
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 cursor-grab active:cursor-grabbing hover:border-primary/20 transition-all group relative animate-fade-in" 
                                    data-task-id="{{ $task->id }}">
                                    
                                    <!-- Priority Badge -->
                                    <div class="flex justify-between items-start mb-2">
                                        @php
                                            $priorityClasses = match($task->priority) {
                                                'high' => 'bg-red-50 text-red-600 border-red-100',
                                                'medium' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'low' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            };
                                            $priorityLabel = match($task->priority) {
                                                'high' => 'Alta',
                                                'medium' => 'Média',
                                                'low' => 'Baixa',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 border {{ $priorityClasses }} text-[8px] font-black uppercase rounded-md tracking-widest">
                                            {{ $priorityLabel }}
                                        </span>
                                        
                                        <button onclick="openEditTaskModal({{ json_encode($task) }})" class="opacity-0 group-hover:opacity-100 transition-opacity text-slate-300 hover:text-primary">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </button>
                                    </div>

                                    <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2">{{ $task->title }}</h4>
                                    
                                    @if($task->description)
                                        <p class="text-[10px] text-slate-400 font-medium line-clamp-2 mb-3">{{ $task->description }}</p>
                                    @endif

                                    <div class="flex justify-between items-center mt-auto pt-3 border-t border-slate-50">
                                        <div class="flex items-center gap-1.5 grayscale opacity-60">
                                            @if($task->due_date)
                                                <span class="material-symbols-outlined text-[10px] text-slate-400">event</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $task->due_date->format('d/m') }}</span>
                                            @endif
                                        </div>

                                        <div class="flex -space-x-1.5">
                                            @if($task->assignee)
                                                <div class="size-5 rounded-full bg-primary border-2 border-white flex items-center justify-center text-[8px] text-white font-black uppercase" title="Atribuído a: {{ $task->assignee->name }}">
                                                    {{ substr($task->assignee->name, 0, 1) }}
                                                </div>
                                            @else
                                                <div class="size-5 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-slate-300" title="Sem ninguém atribuído">
                                                    <span class="material-symbols-outlined text-[10px]">person</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modais (Criação/Edição) -->
    @include('admin.corridas.partials.kanban-modals')

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sortable para as COLUNAS
            const board = document.getElementById('kanban-board');
            new Sortable(board, {
                animation: 150,
                handle: '.cursor-move',
                ghostClass: 'bg-slate-100',
                onEnd: function(evt) {
                    updateColumnOrder();
                }
            });

            // Sortable para as TAREFAS
            const columns = document.querySelectorAll('.kanban-column');
            
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban',
                    animation: 150,
                    ghostClass: 'bg-slate-50/50',
                    dragClass: 'shadow-2xl',
                    onEnd: function(evt) {
                        const taskId = evt.item.dataset.taskId;
                        const newColumnId = evt.to.dataset.columnId;
                        const newOrder = Array.from(evt.to.children).indexOf(evt.item);

                        updateTaskPosition(taskId, newColumnId, newOrder);
                    }
                });
            });
        });

        function updateColumnOrder() {
            const columns = Array.from(document.querySelectorAll('.kanban-column-wrapper')).map((el, index) => ({
                id: el.dataset.columnId,
                order: index
            }));

            fetch("{{ route('admin.kanban.columns.update-order') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ columns: columns })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) toast('Ordem das colunas salva', 'success');
            });
        }

        function updateTaskPosition(taskId, columnId, order) {
            fetch("{{ route('admin.kanban.update-order') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    task_id: taskId,
                    column_id: columnId,
                    new_order: order
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    toast('Posição atualizada', 'success');
                }
            })
            .catch(err => toast('Erro ao atualizar posição', 'error'));
        }

        function openCreateTaskModal() {
            document.getElementById('task-modal-title').innerText = 'Nova Tarefa';
            document.getElementById('task-form').action = "{{ route('admin.kanban.tasks.store', $event->id) }}";
            document.getElementById('task-form-method').value = 'POST';
            document.getElementById('task-title').value = '';
            document.getElementById('task-description').value = '';
            document.getElementById('task-priority').value = 'medium';
            document.getElementById('task-assignee').value = '';
            document.getElementById('task-due-date').value = '';
            document.getElementById('task-modal').classList.remove('hidden');
            document.getElementById('task-modal').classList.add('flex');
        }

        function openEditTaskModal(task) {
            document.getElementById('task-modal-title').innerText = 'Editar Tarefa';
            document.getElementById('task-form').action = `/admin/kanban/tasks/${task.id}`;
            document.getElementById('task-form-method').value = 'PUT';
            document.getElementById('task-title').value = task.title;
            document.getElementById('task-description').value = task.description || '';
            document.getElementById('task-priority').value = task.priority;
            document.getElementById('task-assignee').value = task.assigned_to || '';
            if(task.due_date) {
                document.getElementById('task-due-date').value = task.due_date.split('T')[0];
            }
            document.getElementById('task-modal').classList.remove('hidden');
            document.getElementById('task-modal').classList.add('flex');
        }

        function closeTaskModal() {
            document.getElementById('task-modal').classList.add('hidden');
            document.getElementById('task-modal').classList.remove('flex');
        }

        function openCreateColumnModal() {
            document.getElementById('column-modal').classList.remove('hidden');
            document.getElementById('column-modal').classList.add('flex');
        }

        function closeColumnModal() {
            document.getElementById('column-modal').classList.add('hidden');
            document.getElementById('column-modal').classList.remove('flex');
        }

        function deleteColumn(columnId, columnName, taskCount) {
            if (taskCount > 0) {
                toast(`A coluna "${columnName}" contém ${taskCount} tarefa(s). Mova ou exclua as tarefas primeiro.`, 'error');
                return;
            }

            if (!confirm(`Tem certeza que deseja excluir a coluna "${columnName}"?`)) {
                return;
            }

            fetch(`/admin/kanban/columns/${columnId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    toast('Coluna excluída com sucesso!', 'success');
                    // Remover a coluna da UI
                    document.querySelector(`[data-column-id="${columnId}"]`).remove();
                } else {
                    toast(data.error || 'Erro ao excluir coluna', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                toast('Erro ao excluir coluna', 'error');
            });
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        
        .kanban-column { min-height: 50px; }
        
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    </style>
    @endpush
@endsection
