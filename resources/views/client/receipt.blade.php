@extends('layouts.app')

@section('title', 'Comprovante de Inscrição - ' . $event->name)

@section('content')
    <main class="pt-32 pb-24 px-6 lg:px-12 bg-background-soft min-h-screen">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center size-20 bg-emerald-50 text-emerald-500 rounded-full mb-6">
                    <span class="material-symbols-outlined text-5xl">verified</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter leading-none mb-4">
                    COMPROVANTE DE <span class="text-emerald-500">INSCRIÇÃO</span>
                </h1>
                <p class="text-slate-500 font-medium">
                    Sua vaga está confirmada! Guarde este comprovante para apresentar no dia do evento.
                </p>
            </div>

            {{-- Comprovante Principal --}}
            <div class="bg-white card-shadow border border-slate-100 overflow-hidden mb-8" id="receipt-content">
                {{-- Cabeçalho com Evento --}}
                <div class="bg-gradient-to-br from-primary to-secondary p-8 md:p-12 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                    
                    <div class="relative z-10">
                        <p class="text-xs font-black uppercase tracking-widest opacity-80 mb-2">Corrida</p>
                        <h2 class="text-3xl md:text-4xl font-black uppercase italic tracking-tight mb-4">
                            {{ $event->name }}
                        </h2>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">calendar_today</span>
                                <span class="font-bold">{{ $event->event_date->translatedFormat('d \d\e F, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">schedule</span>
                                <span class="font-bold">06:00 AM</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">location_on</span>
                                <span class="font-bold">{{ $event->city }}, {{ $event->state }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informações do Atleta --}}
                <div class="p-8 md:p-12 border-b border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Dados do Atleta</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Nome Completo</p>
                            <p class="text-lg font-bold">{{ $order->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">CPF</p>
                            <p class="text-lg font-bold font-mono">{{ substr($order->user->cpf, 0, 3) }}.{{ substr($order->user->cpf, 3, 3) }}.{{ substr($order->user->cpf, 6, 3) }}-{{ substr($order->user->cpf, 9, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Email</p>
                            <p class="text-sm font-medium text-slate-600">{{ $order->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Telefone</p>
                            <p class="text-sm font-medium text-slate-600">{{ $order->user->phone ?? 'Não informado' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Informações da Inscrição --}}
                <div class="p-8 md:p-12 border-b border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Detalhes da Inscrição</h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Categoria</p>
                            <p class="text-lg font-bold">{{ $category->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Distância</p>
                            <p class="text-lg font-bold">{{ $category->distance }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Número do Kit</p>
                            <p class="text-2xl font-black italic text-primary">{{ $ticket->ticket_number }}</p>
                        </div>
                    </div>
                </div>

                {{-- QR Code e Código --}}
                <div class="p-8 md:p-12 bg-slate-50">
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-shrink-0">
                            <div class="bg-white p-6 border-2 border-slate-200">
                                <div class="w-48 h-48 bg-white flex items-center justify-center overflow-hidden">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $ticket->ticket_number }}" 
                                         alt="QR Code de Retirada" 
                                         class="w-full h-full object-contain">
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-lg font-black uppercase italic mb-2">Código de Retirada</h3>
                            <p class="text-sm text-slate-600 mb-4">
                                Apresente este código no dia da retirada do kit
                            </p>
                            <div class="bg-white border-2 border-primary/20 rounded-2xl p-6 inline-block">
                                <p class="text-4xl font-black font-mono tracking-wider text-primary">
                                    {{ $ticket->ticket_number }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informações de Pagamento --}}
                <div class="p-8 md:p-12 border-t border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Informações de Pagamento</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Número do Pedido</p>
                            <p class="text-sm font-bold font-mono">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Forma de Pagamento</p>
                            <p class="text-sm font-bold">{{ $payment ? ucfirst($payment->payment_method) : 'Gratuito' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Data do Pagamento</p>
                            <p class="text-sm font-bold">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Valor Total</p>
                            <p class="text-xl font-black italic text-emerald-600">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="p-6 bg-emerald-50 border-t-2 border-emerald-200">
                    <div class="flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        <p class="text-sm font-black uppercase tracking-widest text-emerald-700">
                            Inscrição Confirmada
                        </p>
                    </div>
                </div>
            </div>

            {{-- Informações Importantes --}}
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-3xl p-8 mb-8">
                <div class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-yellow-600 text-3xl flex-shrink-0">info</span>
                    <div>
                        <h3 class="text-lg font-black uppercase italic mb-3 text-yellow-900">Informações Importantes</h3>
                        <ul class="space-y-2 text-sm text-yellow-800">
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-sm mt-0.5">arrow_right</span>
                                <span><strong>Retirada do Kit:</strong> Apresente este comprovante e um documento com foto</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-sm mt-0.5">arrow_right</span>
                                <span><strong>Local:</strong> {{ $event->location }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-sm mt-0.5">arrow_right</span>
                                <span><strong>Horário de Largada:</strong> 06:00 AM - Chegue com antecedência</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-sm mt-0.5">arrow_right</span>
                                <span><strong>Dúvidas:</strong> Entre em contato através do nosso suporte</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Ações --}}
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <button onclick="window.print()" 
                        class="bg-primary text-white px-8 py-4 rounded-full text-sm font-black uppercase tracking-widest shadow-lg hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">print</span>
                    Imprimir Comprovante
                </button>
                <a href="{{ route('client.dashboard') }}" 
                   class="bg-white border-2 border-slate-200 text-slate-700 px-8 py-4 rounded-full text-sm font-black uppercase tracking-widest hover:border-primary hover:text-primary transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">dashboard</span>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </main>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #receipt-content, #receipt-content * {
                visibility: visible;
            }
            #receipt-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
