@extends('layouts.app')

@section('title', 'Inscrição Confirmada - ' . $order->items->first()->category->event->name)

@section('content')
    <main class="pt-32 pb-24 px-6 lg:px-12 bg-background-soft">
        <div class="max-w-[1000px] mx-auto">
            <div class="text-center mb-16 space-y-6">
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
            </div>

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
                                            {{ $item->category->event->name }}</h2>
                                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Kit
                                            {{ $item->category->name }} ({{ $item->category->distance }})</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                                        <div>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Data
                                                do Evento</span>
                                            <p class="text-sm font-bold italic">
                                                {{ $item->category->event->event_date->translatedFormat('d \d\e F, Y') }}</p>
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
@endsection