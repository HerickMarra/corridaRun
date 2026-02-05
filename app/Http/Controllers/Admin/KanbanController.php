<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\KanbanColumn;
use App\Models\KanbanTask;

class KanbanController extends Controller
{
    public function hub()
    {
        $user = auth()->user();
        $query = Event::query();

        if ($user->role === \App\Enums\UserRole::Organizer) {
            $query->whereHas('managers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $events = $query->with([
            'kanbanColumns.tasks' => function ($q) {
                $q->orderBy('order');
            },
            'kanbanColumns.tasks.assignee',
            'managers'
        ])->get();

        $allTaskIds = $events->flatMap(fn($e) => $e->kanbanColumns->flatMap->tasks->pluck('id'));
        $allTasks = KanbanTask::whereIn('id', $allTaskIds);

        $totalTasks = $allTasks->count();
        $doneTasks = (clone $allTasks)->whereHas('column', fn($q) => $q->where('name', 'Concluído'))->count();

        $priorityDist = [
            'high' => (clone $allTasks)->where('priority', 'high')->count(),
            'medium' => (clone $allTasks)->where('priority', 'medium')->count(),
            'low' => (clone $allTasks)->where('priority', 'low')->count(),
        ];

        $stats = [
            'total_tasks' => $totalTasks,
            'done_tasks' => $doneTasks,
            'pending_tasks' => $totalTasks - $doneTasks,
            'high_priority' => $priorityDist['high'],
            'priority_dist' => $priorityDist,
            'completion_rate' => $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0,
        ];

        return view('admin.kanban.hub', compact('events', 'stats'));
    }

    public function index(Event $event)
    {
        $user = auth()->user();

        // Verificar permissão
        if ($user->role === \App\Enums\UserRole::Organizer) {
            if (!$user->managedEvents->contains($event->id)) {
                abort(403);
            }
        }

        // Inicializar colunas padrão se a corrida não tiver nenhuma
        if ($event->kanbanColumns()->count() === 0) {
            $event->kanbanColumns()->createMany([
                ['name' => 'A Fazer', 'order' => 0, 'color_hex' => '#94a3b8'],
                ['name' => 'Em Andamento', 'order' => 1, 'color_hex' => '#0d59f2'],
                ['name' => 'Concluído', 'order' => 2, 'color_hex' => '#22c55e'],
            ]);
        }

        $event->load(['kanbanColumns.tasks.assignee', 'kanbanColumns.tasks.creator']);
        $managers = $event->managers()->get();

        return view('admin.corridas.kanban', compact('event', 'managers'));
    }

    public function storeTask(Request $request, Event $event)
    {
        $request->validate([
            'column_id' => 'required|exists:kanban_columns,id',
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $column = KanbanColumn::findOrFail($request->column_id);

        if ($column->event_id !== $event->id) {
            return response()->json(['error' => 'Invalid column'], 422);
        }

        $task = KanbanTask::create([
            'column_id' => $column->id,
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'assigned_to' => $request->assigned_to,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'order' => $column->tasks()->count(),
        ]);

        return redirect()->back()->with('success', 'Tarefa criada com sucesso!');
    }

    public function updateTask(Request $request, KanbanTask $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()->with('success', 'Tarefa atualizada!');
    }

    public function storeColumn(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'color_hex' => 'nullable|string|max:7',
        ]);

        $event->kanbanColumns()->create([
            'name' => $request->name,
            'color_hex' => $request->color_hex ?? '#94a3b8',
            'order' => $event->kanbanColumns()->count(),
        ]);

        return redirect()->back()->with('success', 'Coluna criada com sucesso!');
    }

    public function updateColumnOrder(Request $request)
    {
        $request->validate([
            'columns' => 'required|array',
            'columns.*.id' => 'required|exists:kanban_columns,id',
            'columns.*.order' => 'required|integer',
        ]);

        foreach ($request->columns as $colData) {
            KanbanColumn::where('id', $colData['id'])->update(['order' => $colData['order']]);
        }

        return response()->json(['success' => true]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:kanban_tasks,id',
            'column_id' => 'required|exists:kanban_columns,id',
            'new_order' => 'required|integer|min:0',
        ]);

        $task = KanbanTask::findOrFail($request->task_id);

        $task->column_id = $request->column_id;
        $task->order = $request->new_order;
        $task->save();

        return response()->json(['success' => true]);
    }

    public function deleteColumn(KanbanColumn $column)
    {
        // Verificar se a coluna tem tarefas
        if ($column->tasks()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir uma coluna que contém tarefas. Mova ou exclua as tarefas primeiro.'
            ], 422);
        }

        $column->delete();

        return response()->json(['success' => true, 'message' => 'Coluna excluída com sucesso!']);
    }
}
