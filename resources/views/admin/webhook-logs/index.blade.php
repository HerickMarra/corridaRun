@extends('layouts.admin')

@section('title', 'Logs de Webhook - Asaas')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Logs de Webhook - Asaas</h1>
            <form action="{{ route('admin.webhook-logs.destroy-all') }}" method="POST"
                onsubmit="return confirm('Tem certeza que deseja deletar TODOS os logs?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Limpar Todos
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.webhook-logs.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Evento</label>
                        <select name="event" class="form-select">
                            <option value="">Todos os eventos</option>
                            @foreach($events as $event)
                                <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                    {{ $event }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                        <a href="{{ route('admin.webhook-logs.index') }}" class="btn btn-secondary">Limpar</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Logs -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Evento</th>
                                <th>Payment ID</th>
                                <th>Order ID</th>
                                <th>Status</th>
                                <th>Data/Hora</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $log->event }}</span>
                                    </td>
                                    <td>{{ $log->payment_id ?? '-' }}</td>
                                    <td>
                                        @if($log->order_id)
                                            <a href="{{ route('admin.corridas.dashboard', ['event' => $log->order?->items?->first()?->category?->event_id ?? 0]) }}"
                                                class="text-decoration-none">
                                                #{{ $log->order_id }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $log->status_code == 200 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $log->status_code }}
                                        </span>
                                    </td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.webhook-logs.show', $log->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.webhook-logs.destroy', $log->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Deletar este log?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Nenhum log encontrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection