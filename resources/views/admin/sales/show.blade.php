@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.sales.index') }}"
                    class="flex items-center justify-center size-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">
                    Pedido <span class="text-primary">#{{ $order->order_number }}</span>
                </h2>
                @php
                    $statusClasses = [
                        'paid' => 'bg-green-100 text-green-600',
                        'pending' => 'bg-orange-100 text-orange-600',
                        'cancelled' => 'bg-red-100 text-red-600',
                        'refunded' => 'bg-blue-100 text-blue-600',
                    ];
                    $class = $statusClasses[$order->status->value] ?? 'bg-slate-100 text-slate-600';
                @endphp
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $class }}">
                    {{ $order->status->value }}
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium ml-12">Detalhes da inscrição e dados informados pelo corredor.</p>
        </div>

        <div class="flex gap-3">
            @if($order->status !== \App\Enums\OrderStatus::Cancelled && $order->status !== \App\Enums\OrderStatus::Refunded)
                <form action="{{ route('admin.sales.cancel', $order->id) }}" method="POST"
                    onsubmit="return confirm('ATENÇÃO: Deseja realmente cancelar esta inscrição? O ingresso será invalidado e devolvido ao estoque. Nenhum estorno será feito ao cliente automaticamente por esse botão.');">
                    @csrf
                    <button type="submit"
                        class="bg-orange-50 text-orange-600 px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-orange-100 transition-all flex items-center gap-2 border border-orange-100">
                        <span class="material-symbols-outlined text-sm">block</span>
                        Cancelar Ingresso
                    </button>
                </form>
            @endif

            @if($order->status === \App\Enums\OrderStatus::Paid && $order->payments->isNotEmpty())
                <form action="{{ route('checkout.refund', $order->payments->first()->id) }}" method="POST"
                    onsubmit="return confirm('ATENÇÃO: Deseja realmente estornar este pagamento? O valor será devolvido ao cliente e a inscrição cancelada.');">
                    @csrf
                    <button type="submit"
                        class="bg-red-50 text-red-600 px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-100 transition-all flex items-center gap-2 border border-red-100">
                        <span class="material-symbols-outlined text-sm">undo</span>
                        Estornar Pedido
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

        <!-- Left Column: Details -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Items Config -->
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-50">
                    <h3 class="text-sm font-black uppercase italic tracking-tight text-slate-800">Itens e Respostas
                        Personalizadas</h3>
                </div>
                <div class="p-8 divide-y divide-slate-50">
                    @forelse($order->items as $item)
                        <div class="py-6 first:pt-0 last:pb-0">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-lg">
                                        {{ $item->category->event->name ?? 'Evento Desconhecido' }}</h4>
                                    <p class="text-sm font-medium text-primary">
                                        {{ $item->category->name ?? 'Categoria Indefinida' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Valor Unitário
                                    </p>
                                    <p class="font-bold text-slate-800">R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            @if($item->custom_responses && count($item->custom_responses) > 0)
                                <div class="mt-6 bg-slate-50 rounded-2xl p-6">
                                    <h5
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">assignment</span>
                                        Respostas do Formulário
                                    </h5>
                                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                                        @foreach($item->custom_responses as $question => $answer)
                                            @php
                                                $fieldLabel = $question;
                                                if (is_numeric($question) && isset($item->category->event->customFields)) {
                                                    $field = $item->category->event->customFields->firstWhere('id', (int) $question);
                                                    if ($field) {
                                                        $fieldLabel = $field->label;
                                                    }
                                                }
                                            @endphp
                                            <div>
                                                <dt class="text-xs font-bold text-slate-500 mb-1 break-words">{{ $fieldLabel }}</dt>
                                                <dd
                                                    class="text-sm font-semibold text-slate-800 break-words bg-white p-3 rounded-lg border border-slate-200/60 shadow-sm">
                                                    {{ is_array($answer) ? implode(', ', $answer) : $answer }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                </div>
                            @else
                                <div class="mt-6 bg-slate-50 rounded-2xl p-6 flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-slate-300">check_box_outline_blank</span>
                                    <p class="text-xs font-bold text-slate-400">Nenhum formulário personalizado respondido neste
                                        ingresso.</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum item encontrado.</p>
                    @endforelse
                </div>
            </div>

            <!-- Payment Block -->
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="text-sm font-black uppercase italic tracking-tight text-slate-800">Histórico de Pagamento
                    </h3>
                    <span
                        class="px-3 py-1 bg-slate-100 rounded-full text-[9px] font-black uppercase tracking-widest text-slate-500">
                        {{ $order->payment_method ?? 'N/A' }}
                    </span>
                </div>
                <div class="p-8">
                    @if($order->payments->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($order->payments as $payment)
                                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="size-10 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm">
                                                    <span class="material-symbols-outlined text-sm">
                                                        @if($payment->payment_method == 'pix') pix
                                                        @elseif($payment->payment_method == 'credit_card') credit_card
                                                        @else account_balance @endif
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-black uppercase tracking-widest text-slate-500">
                                                        {{ $payment->payment_method }}</p>
                                                    <p class="text-[10px] font-medium text-slate-400 mt-1">
                                                        {{ Carbon\Carbon::parse($payment->created_at)->format('d/m/Y H:i:s') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md mb-1 inline-block
                                                                {{ $payment->status === 'RECEIVED' ? 'bg-green-100 text-green-600' :
                                ($payment->status === 'PENDING' ? 'bg-orange-100 text-orange-600' : 'bg-slate-200 text-slate-600') }}">
                                                    {{ $payment->status }}
                                                </span>
                                                <p class="text-sm font-bold text-slate-800 block">R$
                                                    {{ number_format($payment->amount, 2, ',', '.') }}</p>
                                            </div>
                                        </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 font-medium">Nenhuma transação de pagamento registrada.</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column: Summary & User details -->
        <div class="space-y-8">

            <!-- Resumo Financeiro -->
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8">
                <h3 class="text-sm font-black uppercase italic tracking-tight text-slate-800 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-lg">receipt_long</span>
                    Resumo do Pedido
                </h3>

                <div class="space-y-4 text-sm font-medium text-slate-500 mb-6">
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg">
                        <span>Data do Pedido</span>
                        <span class="font-bold text-slate-800">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                        <span>Subtotal Itens</span>
                        <span>R$ {{ number_format($order->items->sum('unit_price'), 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                        <span>Taxa de Serviço</span>
                        <span>R$ {{ number_format($order->service_fee, 2, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-xs font-black uppercase tracking-widest text-slate-400">Total Pago</span>
                    <span class="text-2xl font-black text-slate-800 italic">R$
                        {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                </div>
            </div>

            <!-- Dados do Corredor -->
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8">
                <h3 class="text-sm font-black uppercase italic tracking-tight text-slate-800 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500 text-lg">person</span>
                    Dados do Corredor
                </h3>

                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Nome Completo</p>
                        <p class="font-bold text-slate-800">{{ $order->user->name }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">E-mail</p>
                        <p class="font-bold text-slate-800">{{ $order->user->email }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Documento (CPF)</p>
                        <p class="font-bold text-slate-800">{{ $order->user->cpf ?? 'Não informado' }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Telefone/WhatsApp
                        </p>
                        <p class="font-bold text-slate-800">{{ $order->user->phone ?? 'Não informado' }}</p>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.athletes.edit', ['athlete' => $order->user->id]) }}"
                            class="w-full bg-slate-50 text-slate-600 block text-center py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
                            Ver Perfil Completo
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection