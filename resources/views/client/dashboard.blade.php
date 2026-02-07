@extends('layouts.client')

@section('title', 'HUB DO ATLETA')

@section('content')
    <section class="mb-16">
        <span class="text-primary font-black uppercase tracking-[0.3em] text-[10px] mb-3 block">Membro Premium</span>
        <h1 class="text-5xl md:text-6xl font-black text-secondary uppercase italic leading-[0.95] tracking-tighter">
            Olá, {{ explode(' ', auth()->user()->name)[0] }},<br />
            @php
                $hasUpcoming = $subscriptions->count() > 0;
                $hasPast = $totalPastEvents > 0;
            @endphp
            
            @if(!$hasUpcoming && !$hasPast)
                {{-- Nunca correu --}}
                vamos quebrar a <span class="text-primary">primeira meta?</span>
            @elseif($hasUpcoming)
                {{-- Tem corridas próximas --}}
                sua próxima meta <span class="text-primary">está próxima.</span>
            @else
                {{-- Já correu mas não tem próximas --}}
                hora de <span class="text-primary">superar limites</span> novamente!
            @endif
        </h1>
    </section>

    {{-- Alerta de Pagamentos Pendentes --}}
    @if($pendingOrders->count() > 0)
        <div class="mb-12 space-y-4">
            @foreach($pendingOrders as $order)
                @php
                    $payment = $order->payments->first();
                    $paymentCreatedAt = $payment->created_at;
                    $expirationTime = $paymentCreatedAt->addMinutes(15);
                    $isExpired = now()->greaterThan($expirationTime);
                @endphp

                @if(!$isExpired)
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-3xl p-6 md:p-8 card-shadow"
                         x-data="pixTimer({{ $expirationTime->timestamp }})"
                         x-init="startTimer()">
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                            {{-- Ícone --}}
                            <div class="flex-shrink-0">
                                <div class="inline-flex items-center justify-center size-16 bg-yellow-500/20 text-yellow-600 rounded-full">
                                    <span class="material-symbols-outlined text-4xl">schedule</span>
                                </div>
                            </div>

                            {{-- Conteúdo --}}
                            <div class="flex-1 space-y-2">
                                <h3 class="text-xl font-black uppercase italic tracking-tight text-yellow-900">
                                    Pagamento Pendente
                                </h3>
                                <p class="text-sm text-yellow-800 font-medium">
                                    Você tem um pedido aguardando pagamento para 
                                    <span class="font-bold">{{ $order->items->first()->category->event->name }}</span>.
                                    Complete o pagamento antes que o QR Code expire!
                                </p>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="font-bold text-yellow-700">Tempo restante:</span>
                                    <span class="font-black text-yellow-600 text-lg" x-text="timeRemaining"></span>
                                </div>
                            </div>

                            {{-- Botão --}}
                            <div class="flex-shrink-0">
                                <a href="{{ route('checkout.confirmation', $order->id) }}" 
                                   class="bg-yellow-500 text-white px-6 py-3 rounded-full font-bold uppercase text-sm tracking-widest shadow-lg hover:bg-yellow-600 transition-all inline-flex items-center gap-2">
                                    <span class="material-symbols-outlined text-xl">payment</span>
                                    Pagar Agora
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        <div class="lg:col-span-8 space-y-20">
            <!-- Inscrições Ativas -->
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-black uppercase italic tracking-tight">Inscrições Ativas</h2>
                    <a class="text-sm font-bold text-primary hover:underline" href="{{ route('client.registrations') }}">Ver todas</a>
                </div>

                    @forelse($subscriptions as $subscription)
                        <div class="group relative bg-white border border-slate-100 rounded-3xl overflow-hidden card-shadow hover:card-shadow-hover transition-all duration-500">
                            <div class="flex flex-col md:flex-row">
                                <div class="md:w-2/5 relative h-64 md:h-auto overflow-hidden">
                                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-110 transition-transform duration-1000"
                                        style='background-image: url("{{ $subscription->orderItem->category->event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}");'>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                                </div>
                                <div class="md:w-3/5 p-8 lg:p-10 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-4">
                                            <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider">
                                                {{ $subscription->orderItem->category->name }}
                                            </span>
                                            <div class="text-right">
                                                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Faltam</p>
                                                @php
                                                    $days = now()->diffInDays($subscription->orderItem->category->event->event_date, false);
                                                @endphp
                                                <p class="text-2xl font-black italic text-secondary leading-none">
                                                    {{$days > 0 ? (int) $days : 0 }} DIAS
                                                </p>
                                            </div>
                                        </div>
                                        <h3 class="text-3xl font-black uppercase italic leading-tight mb-2">{{ $subscription->orderItem->category->event->name }}</h3>
                                        <p class="text-slate-500 font-medium mb-6">
                                            {{ $subscription->orderItem->category->event->event_date->translatedFormat('d \d\e F') }} • 
                                            {{ $subscription->orderItem->category->distance }} • 
                                            Kit Confirmado
                                        </p>
                                    </div>
                                    <div class="flex gap-4">
                                        <a href="{{ route('events.show', $subscription->orderItem->category->event->slug) }}"
                                            class="bg-secondary text-white px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-black transition-all">Ver Guia da Prova</a>
                                        <a href="{{ route('client.receipt', $subscription->id) }}" 
                                           class="border border-slate-200 px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm">description</span>
                                            Comprovante
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center border-2 border-dashed border-slate-100 rounded-3xl">
                            <span class="material-symbols-outlined text-slate-300 text-5xl mb-4">event_busy</span>
                            <p class="text-slate-500 font-medium">Você ainda não tem inscrições ativas.</p>
                            <a href="{{ route('home') }}"
                                class="inline-block mt-6 px-8 py-3 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-full">Explorar
                                Corridas</a>
                        </div>
                    @endforelse
            </div>

            <!-- Minha Jornada -->
            <div>
                <h2 class="text-2xl font-black uppercase italic tracking-tight mb-12">Minha Jornada</h2>
                <div class="relative pl-12">
                    <div class="absolute left-[23px] top-0 bottom-0 w-px timeline-line"></div>
                    <div class="space-y-12">
                        @forelse($pastEvents as $event)
                            <div class="relative group">
                                <div
                                    class="absolute -left-[54px] top-0 size-11 bg-white border-2 border-primary rounded-full flex items-center justify-center z-10">
                                    <span class="material-symbols-outlined text-primary text-xl">workspace_premium</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                    <div class="aspect-[16/9] rounded-2xl overflow-hidden shadow-lg">
                                        @php
                                            // Se começa com http/https é URL externa, senão é caminho local
                                            $imageUrl = $event->banner_image 
                                                ? (str_starts_with($event->banner_image, 'http') 
                                                    ? $event->banner_image 
                                                    : asset($event->banner_image))
                                                : 'https://via.placeholder.com/800x450?text=' . urlencode($event->name);
                                        @endphp
                                        <img alt="{{ $event->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                            src="{{ $imageUrl }}" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-2">
                                            {{ $event->event_date?->translatedFormat('F Y') ?? 'Data não disponível' }}
                                        </p>
                                        <h4 class="text-xl font-black uppercase italic mb-2">{{ $event->name }}</h4>
                                        <p class="text-slate-500 text-sm mb-4 leading-relaxed">
                                            {{ $event->description ? Str::limit($event->description, 100) : 'Corrida concluída com sucesso!' }}
                                        </p>
                                        <div class="flex gap-4">
                                            <span class="text-[10px] font-black bg-slate-100 px-3 py-1 rounded uppercase">
                                                {{ $event->event_date?->format('d/m/Y') ?? 'N/A' }}
                                            </span>
                                            @if($event->location)
                                                <span class="text-[10px] font-black bg-slate-100 px-3 py-1 rounded uppercase">
                                                    {{ $event->location }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <span class="material-symbols-outlined text-slate-300 text-6xl mb-4">directions_run</span>
                                <p class="text-slate-400 font-medium">Nenhuma corrida concluída ainda.</p>
                                <p class="text-slate-400 text-sm">Suas conquistas aparecerão aqui!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($totalPastEvents > 5)
                    <div class="text-center mt-8">
                        <a href="{{ route('client.registrations') }}" class="inline-flex items-center gap-2 text-primary hover:text-blue-700 font-bold text-sm transition-colors">
                            <span>Ver todas as {{ $totalPastEvents }} corridas realizadas</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Rights -->
        <div class="lg:col-span-4">
            <div class="sticky top-32 space-y-8">
                <!-- Perfil -->
                <div class="bg-white border border-slate-100 rounded-3xl p-10 card-shadow">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black uppercase italic tracking-tight">Perfil e Preferências</h3>
                        <a href="{{ route('profile.edit') }}" class="text-primary hover:text-blue-700 transition-colors"><span class="material-symbols-outlined">edit_note</span></a>
                    </div>
                    <div class="space-y-8">
                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-2">Informações
                                Pessoais</label>
                            <div class="space-y-4">
                                <div class="flex justify-between border-b border-slate-50 pb-3">
                                    <span class="text-sm font-medium text-slate-500">CPF</span>
                                    <span class="text-sm font-bold">{{ auth()->user()->cpf ?? '---.***.***-00' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-50 pb-3">
                                    <span class="text-sm font-medium text-slate-500">Nascimento</span>
                                    <span
                                        class="text-sm font-bold">{{ auth()->user()->birth_date && auth()->user()->birth_date instanceof \Carbon\Carbon ? auth()->user()->birth_date->format('d/m/Y') : '15/05/1992' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-50 pb-3">
                                    <span class="text-sm font-medium text-slate-500">Equipe</span>
                                    <span class="text-sm font-bold text-primary">{{ auth()->user()->team ?? 'Não informado' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                        class="block w-full mt-10 py-4 bg-slate-50 hover:bg-slate-100 text-slate-500 font-black text-[10px] uppercase tracking-[0.2em] rounded-full transition-all text-center">
                        Editar Perfil
                    </a>
                </div>

                <!-- Pontos -->
                <div class="bg-secondary rounded-3xl p-8 text-white relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                        <svg class="size-24" fill="currentColor" viewBox="0 0 48 48">
                            <path
                                d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z">
                            </path>
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase text-primary tracking-[0.3em] mb-2">Pontos Acumulados</p>
                        <p class="text-4xl font-black italic mb-6">12.450 <span
                                class="text-xs uppercase not-italic tracking-normal text-white/40">pts</span></p>
                        <button
                            class="text-xs font-black uppercase tracking-widest text-primary hover:text-white transition-colors">Resgatar
                            Benefícios →</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        // Recarregar a página para remover o alerta
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
    </script>
@endsection