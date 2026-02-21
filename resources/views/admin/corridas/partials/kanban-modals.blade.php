<!-- Task Modal -->
<div id="task-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center shrink-0">
            <h3 id="task-modal-title" class="text-xl font-black uppercase italic tracking-tighter text-slate-800">Nova
                Tarefa</h3>
            <button onclick="closeTaskModal()" class="text-slate-400 hover:text-black transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="task-form" method="POST" class="p-6 space-y-4 overflow-y-auto">
            @csrf
            <input type="hidden" name="_method" id="task-form-method" value="POST">

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Coluna</label>
                <select name="column_id" id="task-column-id" required
                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none">
                    @foreach($event->kanbanColumns as $column)
                        <option value="{{ $column->id }}">{{ $column->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Título da
                    Tarefa</label>
                <input type="text" name="title" id="task-title" required
                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                    placeholder="Ex: Contratar Ambulância">
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Descrição
                    (Opcional)</label>
                <textarea name="description" id="task-description" rows="3"
                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                    placeholder="Detalhes sobre a execução..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label
                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Prioridade</label>
                    <select name="priority" id="task-priority" required
                        class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none">
                        <option value="low">Baixa</option>
                        <option value="medium" selected>Média</option>
                        <option value="high">Alta</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Data
                        Limite</label>
                    <input type="date" name="due_date" id="task-due-date"
                        class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Responsável</label>
                <select name="assigned_to" id="task-assignee"
                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none">
                    <option value="">Ninguém atribuído</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->role->value }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit"
                    class="flex-grow bg-primary text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Salvar Tarefa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Column Modal -->
<div id="column-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
    <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center shrink-0">
            <h3 class="text-xl font-black uppercase italic tracking-tighter text-slate-800">Nova Coluna</h3>
            <button onclick="closeColumnModal()" class="text-slate-400 hover:text-black transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('admin.kanban.columns.store', $event->id) }}" method="POST"
            class="p-6 space-y-4 overflow-y-auto">
            @csrf
            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome da
                    Coluna</label>
                <input type="text" name="name" required
                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                    placeholder="Ex: Em Espera">
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cor de
                    Destaque</label>
                <input type="color" name="color_hex" value="#94a3b8"
                    class="w-full h-12 bg-slate-50 border-transparent rounded-xl px-2 py-1 focus:bg-white transition-all outline-none cursor-pointer">
            </div>

            <button type="submit"
                class="w-full bg-primary text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-lg">add_column</span>
                Criar Coluna
            </button>
        </form>
    </div>
</div>