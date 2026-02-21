@extends('layouts.client')

@section('title', 'Histórico de Compras - Sisters Esportes')

@section('content')
<main class="py-12 bg-white">
    <div class="max-w-[1200px] mx-auto px-6">
        <section class="mb-12">
            <nav class="flex items-center gap-2 mb-6">
                <a class="text-[10px] font-black uppercase text-slate-400 tracking-widest hover:text-primary" href="{{ route('client.dashboard') }}">Início</a>
                <span class="text-slate-300">/</span>
                <span class="text-[10px] font-black uppercase text-primary tracking-widest">Meus Pedidos</span>
            </nav>
            <h1 class="text-5xl md:text-6xl font-black text-secondary uppercase italic leading-[0.95] tracking-tighter">
                Histórico de <span class="text-primary">Compras</span>
            </h1>
        </section>

        <div class="flex border-b border-slate-100 mb-12 overflow-x-auto no-scrollbar pb-2">
            <a href="{{ route('client.orders') }}" class="px-8 py-4 text-xs font-black uppercase tracking-[0.2em] {{ !request('status') ? 'border-b-2 border-primary text-primary' : 'border-b-2 border-transparent text-slate-400 hover:text-secondary' }} transition-colors whitespace-nowrap">
                Todos
            </a>
            <a href="{{ route('client.orders', ['status' => 'completed']) }}" class="px-8 py-4 text-xs font-black uppercase tracking-[0.2em] {{ request('status') === 'completed' ? 'border-b-2 border-primary text-primary' : 'border-b-2 border-transparent text-slate-400 hover:text-secondary' }} transition-colors flex items-center gap-2 whitespace-nowrap">
                Concluídos
                <span class="size-1.5 bg-success rounded-full"></span>
            </a>
            <a href="{{ route('client.orders', ['status' => 'pending']) }}" class="px-8 py-4 text-xs font-black uppercase tracking-[0.2em] {{ request('status') === 'pending' ? 'border-b-2 border-primary text-primary' : 'border-b-2 border-transparent text-slate-400 hover:text-secondary' }} transition-colors flex items-center gap-2 whitespace-nowrap">
                Aguardando Pagamento
                <span class="size-1.5 bg-warning rounded-full"></span>
            </a>
            <a href="{{ route('client.orders', ['status' => 'cancelled']) }}" class="px-8 py-4 text-xs font-black uppercase tracking-[0.2em] {{ request('status') === 'cancelled' ? 'border-b-2 border-primary text-primary' : 'border-b-2 border-transparent text-slate-400 hover:text-secondary' }} transition-colors flex items-center gap-2 whitespace-nowrap">
                Cancelados
                <span class="size-1.5 bg-red-500 rounded-full"></span>
            </a>
        </div>

        <div class="space-y-6">
            @forelse ($orders as $order)
                <div class="group bg-white border border-slate-100 rounded-[2rem] p-6 md:p-8 card-shadow hover:card-shadow-hover transition-all duration-500">
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div class="flex md:flex-col gap-4 md:gap-1 items-start min-w-[140px]">
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Pedido #{{ $order->order_number }}</p>
                            <p class="text-sm font-bold text-secondary">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        
                        <div class="flex flex-1 flex-col sm:flex-row items-start sm:items-center gap-6 w-full md:w-auto">
                            @if($order->items->first() && $order->items->first()->category->event)
                                <div class="size-20 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-50 flex items-center justify-center">
                                    <img alt="Event Kit" class="w-full h-full object-cover" src="{{ $order->items->first()->category->event->banner_image ?? 'https://ui-avatars.com/api/?name='.urlencode($order->items->first()->category->event->name) }}"/>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-black uppercase italic leading-tight mb-1">{{ $order->items->first()->category->event->name }}</h3>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <span class="text-xs font-bold text-slate-500 uppercase">{{ $order->items->first()->category->name }}</span>
                                        
                                        @if($order->status === \App\Enums\OrderStatus::Paid)
                                            <span class="bg-success/10 text-success text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider">Pago</span>
                                        @elseif($order->status === \App\Enums\OrderStatus::Pending)
                                            <span class="bg-warning/10 text-warning text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider">Aguardando Pagamento</span>
                                        @elseif($order->status === \App\Enums\OrderStatus::Refunded)
                                            <span class="bg-blue-100 text-blue-600 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider">Reembolsado</span>
                                        @else
                                            <span class="bg-red-100 text-red-600 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider">Cancelado/Expirado</span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-bold text-slate-500 mt-2">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                                </div>
                            @else
                                <div class="size-20 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-50 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl text-slate-300">event</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-black uppercase italic leading-tight mb-1 text-slate-400">Evento Indisponível</h3>
                                    <div class="flex items-center gap-3">
                                        @if($order->status === \App\Enums\OrderStatus::Paid)
                                            <span class="bg-success/10 text-success text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider">Pago</span>
                                        @else
                                            <span class="bg-slate-100 text-slate-500 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider">{{ $order->status->value }}</span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-bold text-slate-500 mt-2">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col items-end gap-3 w-full md:w-auto">
                            @if($order->status === \App\Enums\OrderStatus::Pending)
                                @php
                                    // Pega o pix ou boleto se houver
                                    $payment = $order->payments->where('status', \App\Enums\PaymentStatus::Pending)->first();
                                    $paymentUrl = $payment ? route('checkout.confirmation', $order->id) : '#';
                                @endphp
                                <a href="{{ $paymentUrl }}" class="w-full md:w-auto text-center bg-primary text-white px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-widest hover:brightness-110 transition-all">
                                    Pagar Agora
                                </a>
                                {{-- 
                                <button class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-secondary transition-colors">
                                    Detalhes do Pedido
                                </button>
                                --}}
                            @elseif($order->status === \App\Enums\OrderStatus::Paid && $order->items->first()?->ticket)
                                <a href="{{ route('client.receipt', $order->items->first()->ticket->id) }}" class="w-full md:w-auto text-center bg-secondary text-white px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all">
                                    Ver Comprovante
                                </a>
                            @else
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    Finalizado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center bg-slate-50 border border-slate-100 rounded-3xl">
                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">shopping_bag</span>
                    <h3 class="text-lg font-black uppercase italic mb-1">Nenhum pedido encontrado</h3>
                    <p class="text-slate-500 text-sm">Você ainda não possui pedidos com este status.</p>
                </div>
            @endforelse
        </div>
        
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif

        <div class="mt-20 p-12 bg-slate-50 rounded-[2.5rem] border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <h4 class="text-2xl font-black uppercase italic tracking-tight mb-2">Alguma dúvida sobre seus pedidos?</h4>
                <p class="text-slate-500 font-medium">Nossa equipe de suporte está pronta para te ajudar com qualquer questão financeira ou de inscrição.</p>
            </div>
            <a href="https://wa.me/seu_numero_aqui" target="_blank" class="border-2 border-secondary px-8 py-4 rounded-full text-xs font-black uppercase tracking-widest hover:bg-secondary hover:text-white transition-all whitespace-nowrap">
                Central de Ajuda
            </a>
        </div>
    </div>
</main>
@endsection
