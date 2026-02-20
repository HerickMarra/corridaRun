@extends('layouts.app')

@section('title', 'Minhas Inscrições')

@section('content')
    <main class="pt-32 pb-24 px-6 lg:px-12">
        <div class="max-w-[1440px] mx-auto">
            <section class="mb-12">
                <h1
                    class="text-4xl md:text-5xl font-black text-secondary uppercase italic leading-none tracking-tighter mb-4">
                    Minhas <span class="text-primary">Inscrições</span>
                </h1>
                <p class="text-slate-500 font-medium">Confira abaixo suas inscrições ativas e detalhes dos eventos.</p>
            </section>

            <div class="space-y-6">
                @forelse($activeRegistrations as $ticket)
                    @php
                        $event = $ticket->orderItem->category->event;
                        $category = $ticket->orderItem->category;
                        $order = $ticket->orderItem->order;
                    @endphp
                    <div
                        class="group relative bg-white border border-slate-100 rounded-3xl overflow-hidden card-shadow hover:card-shadow-hover transition-all duration-500">
                        <div class="flex flex-col md:row items-stretch md:flex-row">
                            <div class="md:w-[320px] relative h-64 md:h-auto overflow-hidden flex-shrink-0">
                                <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-1000"
                                    style="background-image: url('{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}');">
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-r from-black/10 to-transparent"></div>
                            </div>
                            <div class="flex-grow p-8 lg:p-10 flex flex-col md:flex-row justify-between items-center gap-8">
                                <div class="w-full">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest text-primary bg-primary/5 px-2 py-0.5 rounded">
                                            {{ $event->status->value }}
                                        </span>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                            ID: #{{ $order->order_number }}
                                        </span>
                                    </div>
                                    <h3 class="text-3xl font-black uppercase italic leading-tight mb-2 tracking-tight">
                                        {{ $event->name }}
                                    </h3>
                                    <div class="grid grid-cols-2 md:flex md:items-center gap-x-8 gap-y-2 mt-4">
                                        <div>
                                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Data
                                            </p>
                                            <p class="text-sm font-bold text-secondary">
                                                {{ $event->event_date->translatedFormat('d \d\e F, Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">
                                                Localização</p>
                                            <p class="text-sm font-bold text-secondary">{{ $event->city }}, {{ $event->state }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Kit
                                                Escolhido</p>
                                            <p class="text-sm font-bold text-secondary">{{ $category->name }} -
                                                {{ $category->distance }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">
                                                Status</p>
                                            <p class="text-sm font-bold text-green-600">{{ $ticket->status->value }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row gap-3 w-full md:w-auto">
                                    <a href="{{ route('client.receipt', $ticket->id) }}"
                                        class="flex-1 whitespace-nowrap border border-slate-200 px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all text-center flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-sm">description</span>
                                        Comprovante
                                    </a>
                                    <a href="{{ route('events.show', $event->slug) }}"
                                        class="flex-1 whitespace-nowrap bg-secondary text-white px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all text-center">
                                        Guia do Atleta
                                    </a>

                                    @php
                                        $canRefund = false;
                                        if ($ticket->orderItem->order->status === \App\Enums\OrderStatus::Paid) {
                                            $payment = $ticket->orderItem->order->payments->first();
                                            if ($payment && $event->allow_user_refund) {
                                                $daysSincePayment = $payment->paid_at ? $payment->paid_at->diffInDays(now()) : 999;
                                                $hoursToEvent = now()->diffInHours($event->event_date, false);

                                                if ($daysSincePayment <= 7 && $hoursToEvent >= 48) {
                                                    $canRefund = true;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if($ticket->orderItem->order->status === \App\Enums\OrderStatus::Refunded)
                                        <div
                                            class="flex-1 whitespace-nowrap bg-red-100 text-red-600 border border-red-200 px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest text-center cursor-not-allowed opacity-75">
                                            Reembolsado
                                        </div>
                                    @elseif($canRefund)
                                        <form action="{{ route('checkout.refund', $payment->id) }}" method="POST" class="flex-1"
                                            onsubmit="return confirm('Tem certeza que deseja cancelar sua inscrição e solicitar o reembolso? Esta ação é irreversível.');">
                                            @csrf
                                            <button type="submit"
                                                class="w-full whitespace-nowrap bg-red-50 text-red-500 border border-red-100 px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all text-center">
                                                Solicitar Reembolso
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center border-2 border-dashed border-slate-100 rounded-3xl">
                        <span class="material-symbols-outlined text-slate-300 text-5xl mb-4">event_busy</span>
                        <p class="text-slate-500 font-medium">Você não tem inscrições ativas no momento.</p>
                        <a href="{{ route('home') }}"
                            class="inline-block mt-6 px-8 py-3 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-primary/90 transition-all">
                            Explorar Corridas
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
@endsection