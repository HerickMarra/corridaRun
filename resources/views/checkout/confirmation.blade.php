@extends('layouts.app')

@section('title', 'Inscrição Confirmada - ' . $order->items->first()->category->event->name)

@section('content')
    <main class="pt-32 pb-24 px-6 lg:px-12 bg-background-soft">
        <div class="max-w-[1000px] mx-auto">
            <div class="text-center mb-16 space-y-6">
                @if($order->status === \App\Enums\OrderStatus::Pending)
                    <div class="inline-flex items-center justify-center size-24 bg-yellow-50 text-yellow-500 rounded-full mb-4">
                        <span class="material-symbols-outlined text-5xl">pending</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter leading-none">
                        Aguardando <span class="text-primary">Pagamento</span>
                    </h1>
                    <p class="text-slate-500 font-medium max-w-lg mx-auto">
                        Quase lá, {{ explode(' ', auth()->user()->name)[0] }}! Realize o pagamento para confirmar sua inscrição
                        na
                        {{ $order->items->first()->category->event->name }}.
                    </p>
                @else
                    <div
                        class="inline-flex items-center justify-center size-24 bg-emerald-50 text-emerald-500 rounded-full mb-4">
                        <span class="material-symbols-outlined text-5xl">check_circle</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter leading-none">
                        SUA VAGA ESTÁ <span class="text-primary">GARANTIDA!</span>
                    </h1>
                    <p class="text-slate-500 font-medium max-w-lg mx-auto">
                        Parabéns, {{ explode(' ', auth()->user()->name)[0] }}! Sua inscrição para a
                        {{ $order->items->first()->category->event->name }} foi confirmada com sucesso. Prepare seus tênis.
                    </p>
                @endif
            </div>



            @if($order->status === \App\Enums\OrderStatus::Pending && $payment)
                @php
                    $paymentCreatedAt = $payment->created_at;
                    $expirationTime = $paymentCreatedAt->addMinutes(15);
                    $isExpired = now()->greaterThan($expirationTime);
                    $minutesRemaining = !$isExpired ? now()->diffInMinutes($expirationTime, false) : 0;
                @endphp

                <div class="mb-12">
                    @if($payment->payment_method === 'pix' && $payment->pix_qr_code_base64)
                        @if($isExpired)
                            {{-- QR Code Expirado --}}
                            <div class="bg-white rounded-3xl p-8 card-shadow border-2 border-red-200 max-w-md mx-auto text-center">
                                <div class="inline-flex items-center justify-center size-16 bg-red-50 text-red-500 rounded-full mb-4">
                                    <span class="material-symbols-outlined text-4xl">schedule</span>
                                </div>
                                <h3 class="text-lg font-black uppercase italic mb-4 text-red-600">QR Code Expirado</h3>
                                <p class="text-slate-600 text-sm mb-6">
                                    O QR Code Pix expirou após 15 minutos. Por favor, faça um novo pedido para gerar um novo código de
                                    pagamento.
                                </p>
                                <a href="{{ route('events.show', $order->items->first()->category->event->slug) }}"
                                    class="bg-primary text-white px-8 py-4 rounded-full font-bold uppercase tracking-widest shadow-lg hover:bg-primary/90 transition-all inline-block">
                                    Fazer Novo Pedido
                                </a>
                            </div>
                        @else
                            {{-- QR Code Ativo --}}
                            <div class="bg-white rounded-3xl p-8 card-shadow border-2 border-primary/20 max-w-md mx-auto text-center"
                                x-data="pixTimer({{ $expirationTime->timestamp }})" x-init="startTimer()">

                                {{-- Contador de Tempo --}}
                                <div class="mb-4 p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                                    <p class="text-xs font-bold uppercase tracking-widest text-yellow-700 mb-1">Tempo Restante</p>
                                    <p class="text-2xl font-black text-yellow-600" x-text="timeRemaining"></p>
                                </div>

                                <h3 class="text-lg font-black uppercase italic mb-6">Escaneie o QR Code</h3>
                                <img src="data:image/png;base64,{{ $payment->pix_qr_code_base64 }}" alt="Pix QR Code"
                                    class="w-64 h-64 mx-auto mb-6">
                                <div class="bg-slate-50 p-4 rounded-xl break-all text-xs font-mono text-slate-500 mb-4 select-all">
                                    {{ $payment->pix_qr_code }}
                                </div>
                                <button onclick="navigator.clipboard.writeText('{{ $payment->pix_qr_code }}'); alert('Código copiado!')"
                                    class="text-primary font-bold uppercase text-xs hover:underline">
                                    Copiar Código Pix
                                </button>
                            </div>
                        @endif
                    @elseif($payment->invoice_url)
                        <div class="bg-white rounded-3xl p-8 card-shadow border-2 border-primary/20 max-w-md mx-auto text-center">
                            <h3 class="text-lg font-black uppercase italic mb-6">Pagamento via
                                {{ ucfirst($payment->payment_method) }}
                            </h3>
                            <p class="text-slate-500 text-sm mb-6">Clique no botão abaixo para acessar o link de pagamento.</p>
                            <a href="{{ $payment->invoice_url }}" target="_blank"
                                class="bg-primary text-white px-8 py-4 rounded-full font-bold uppercase tracking-widest shadow-lg hover:bg-primary/90 transition-all">
                                Pagar Agora
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid lg:grid-cols-5 gap-8 items-start">
                <div class="lg:col-span-3 space-y-6">
                    @foreach($order->items as $item)
                        <div class="bg-white rounded-3xl p-8 md:p-10 card-shadow border border-slate-50">
                            <div class="flex flex-col md:flex-row gap-8 items-center md:items-start text-center md:text-left">
                                <div class="w-full md:w-48 space-y-4">
                                    <div
                                        class="aspect-square bg-white border-2 border-slate-100 rounded-2xl p-4 flex items-center justify-center bg-slate-50">
                                        <span class="material-symbols-outlined text-7xl text-slate-900">qr_code_2</span>
                                    </div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-primary text-center">Código
                                        do Kit: {{ $item->ticket->ticket_number }}</p>
                                </div>
                                <div class="flex-1 space-y-6">
                                    <div>
                                        <h2 class="text-2xl font-black uppercase italic tracking-tight mb-1">
                                            {{ $item->category->event->name }}
                                        </h2>
                                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Kit
                                            {{ $item->category->name }} ({{ $item->category->distance }})
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                                        <div>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Data
                                                do Evento</span>
                                            <p class="text-sm font-bold italic">
                                                {{ $item->category->event->event_date->translatedFormat('d \d\e F, Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Horário
                                                Largada</span>
                                            <p class="text-sm font-bold italic">06:00 AM</p>
                                        </div>
                                        <div class="col-span-2">
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Local
                                                da Retirada</span>
                                            <p class="text-sm font-bold italic">{{ $item->category->event->location }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-primary/5 p-4 rounded-2xl border border-primary/10">
                                        <p class="text-[11px] font-bold text-primary flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm">info</span>
                                            Apresente este código no dia da retirada
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl p-8 card-shadow border border-slate-50">
                        <h3 class="text-sm font-black uppercase italic tracking-widest mb-6 border-b border-slate-100 pb-4">
                            Resumo do Pedido</h3>
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-medium text-slate-500 uppercase tracking-widest text-[10px]">Inscrição
                                    #{{ $order->order_number }}</span>
                                <span class="font-bold">R$
                                    {{ number_format($order->total_amount - $serviceFee, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-medium text-slate-500 uppercase tracking-widest text-[10px]">Taxa de
                                    Serviço</span>
                                <span class="font-bold">R$ {{ number_format($serviceFee, 2, ',', '.') }}</span>
                            </div>
                            <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                                <span class="text-xs font-black uppercase tracking-widest">Total Pago</span>
                                <span class="text-xl font-black italic text-primary">R$
                                    {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <a href="{{ route('client.dashboard') }}"
                                class="w-full bg-slate-900 text-white py-4 rounded-full text-[10px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-2 hover:bg-primary transition-all">
                                <span class="material-symbols-outlined text-lg">dashboard</span>
                                Ir para o Hub do Atleta
                            </a>
                            <button
                                class="w-full bg-white border border-slate-200 text-slate-600 py-4 rounded-full text-[10px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-2 hover:border-primary hover:text-primary transition-all">
                                <span class="material-symbols-outlined text-lg">description</span>
                                Ver Guia do Atleta
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4">
                        <button onclick="window.print()"
                            class="text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-primary transition-colors">
                            Imprimir Comprovante
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function pixTimer(expirationTimestamp) {
            return {
                timeRemaining: '',
                interval: null,

                startTimer() {
                    this.updateTimer();
                    this.interval = setInterval(() => {
                        this.updateTimer();
                    }, 1000);
                },

                updateTimer() {
                    const now = Math.floor(Date.now() / 1000);
                    const secondsLeft = expirationTimestamp - now;

                    if (secondsLeft <= 0) {
                        this.timeRemaining = 'Expirado';
                        clearInterval(this.interval);
                        // Recarregar a página para mostrar a mensagem de expirado
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                        return;
                    }

                    const minutes = Math.floor(secondsLeft / 60);
                    const seconds = secondsLeft % 60;
                    this.timeRemaining = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
            }
        }

        // Verificador de pagamento Pix
        @if($order->status === \App\Enums\OrderStatus::Pending && $payment && $payment->payment_method === 'pix')
            let paymentCheckInterval;
            let checkCount = 0;
            const maxChecks = 180; // 15 minutos (180 checks * 5 segundos)

            function checkPaymentStatus() {
                checkCount++;

                // Parar após 15 minutos
                if (checkCount > maxChecks) {
                    clearInterval(paymentCheckInterval);
                    console.log('Verificação de pagamento encerrada após 15 minutos');
                    return;
                }

                fetch('{{ route('checkout.payment.status', $order->id) }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status do pagamento:', data);

                        if (data.is_paid) {
                            clearInterval(paymentCheckInterval);

                            // Mostrar mensagem de sucesso
                            alert('🎉 Pagamento confirmado! Redirecionando para o dashboard...');

                            // Redirecionar para o dashboard
                            window.location.href = '{{ route('client.dashboard') }}';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao verificar pagamento:', error);
                    });
            }

            // Iniciar verificação a cada 5 segundos
            paymentCheckInterval = setInterval(checkPaymentStatus, 5000);

            // Primeira verificação imediata
            checkPaymentStatus();
        @endif
    </script>
@endsection