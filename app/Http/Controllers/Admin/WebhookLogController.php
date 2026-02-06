<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebhookLog;
use Illuminate\Http\Request;

class WebhookLogController extends Controller
{
    public function index(Request $request)
    {
        $query = WebhookLog::query()->latest();

        // Filtro por evento
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filtro por data
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(20);
        $events = WebhookLog::distinct()->pluck('event');

        return view('admin.webhook-logs.index', compact('logs', 'events'));
    }

    public function show($id)
    {
        $log = WebhookLog::with('order')->findOrFail($id);
        return view('admin.webhook-logs.show', compact('log'));
    }

    public function destroy($id)
    {
        $log = WebhookLog::findOrFail($id);
        $log->delete();

        return redirect()->route('admin.webhook-logs.index')
            ->with('success', 'Log deletado com sucesso!');
    }

    public function destroyAll()
    {
        $count = WebhookLog::count();
        WebhookLog::truncate();

        return redirect()->route('admin.webhook-logs.index')
            ->with('success', "{$count} logs deletados com sucesso!");
    }
}
