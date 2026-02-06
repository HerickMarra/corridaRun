@extends('layouts.admin')

@section('title', 'Detalhes do Webhook Log')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Detalhes do Webhook Log #{{ $log->id }}</h1>
            <a href="{{ route('admin.webhook-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informações Gerais</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">ID:</th>
                                <td>{{ $log->id }}</td>
                            </tr>
                            <tr>
                                <th>Evento:</th>
                                <td><span class="badge bg-info">{{ $log->event }}</span></td>
                            </tr>
                            <tr>
                                <th>Payment ID:</th>
                                <td>{{ $log->payment_id ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Order ID:</th>
                                <td>
                                    @if($log->order_id)
                                        <a
                                            href="{{ route('admin.corridas.dashboard', ['event' => $log->order?->items?->first()?->category?->event_id ?? 0]) }}">
                                            #{{ $log->order_id }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status Code:</th>
                                <td>
                                    <span class="badge {{ $log->status_code == 200 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $log->status_code }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Processado em:</th>
                                <td>{{ $log->processed_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Criado em:</th>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            @if($log->order)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Informações do Pedido</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Número:</th>
                                    <td>{{ $log->order->order_number }}</td>
                                </tr>
                                <tr>
                                    <th>Cliente:</th>
                                    <td>{{ $log->order->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $log->order->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>R$ {{ number_format($log->order->total_amount, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $log->order->status->color() }}">
                                            {{ $log->order->status->label() }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payload Completo</h5>
            </div>
            <div class="card-body">
                <pre class="bg-dark text-light p-3 rounded"
                    style="max-height: 600px; overflow-y: auto;"><code>{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code></pre>
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('admin.webhook-logs.destroy', $log->id) }}" method="POST"
                onsubmit="return confirm('Tem certeza que deseja deletar este log?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Deletar Log
                </button>
            </form>
        </div>
    </div>
@endsection