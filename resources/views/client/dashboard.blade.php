@extends('layouts.client')

@section('title', 'HUB DO ATLETA')

@section('content')
    <section class="mb-16">
        <span class="text-primary font-black uppercase tracking-[0.3em] text-[10px] mb-3 block">Membro Premium</span>
        <h1 class="text-5xl md:text-6xl font-black text-secondary uppercase italic leading-[0.95] tracking-tighter">
            Olá, {{ explode(' ', auth()->user()->name)[0] }},<br />sua próxima meta <span class="text-primary">está
                próxima.</span>
        </h1>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        <div class="lg:col-span-8 space-y-20">
            <!-- Inscrições Ativas -->
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-black uppercase italic tracking-tight">Inscrições Ativas</h2>
                    <a class="text-sm font-bold text-primary hover:underline" href="#">Ver todas</a>
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
                                                    {{ $days > 0 ? $days : 0 }} DIAS
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
                                        <button class="border border-slate-200 px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Voucher</button>
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
                        <div class="relative group">
                            <div
                                class="absolute -left-[54px] top-0 size-11 bg-white border-2 border-primary rounded-full flex items-center justify-center z-10">
                                <span class="material-symbols-outlined text-primary text-xl">workspace_premium</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="aspect-[16/9] rounded-2xl overflow-hidden shadow-lg">
                                    <img alt="Past Race"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDRtGZ2mrAs-saSWAihjeRaTnwzFCoItB2zAzdDo3KT6l1tgpsihCTyK9v8xvnFcvnO0uUbnFbCBbaQjVYkZfJzf1ZhvZoxN3DsvfIywZghgWRppvQe09hSTEeuiPbkj5tSiXwDWJBMZimfTy1Wa9vdM1ZkuD-kpcUN5Y0uMdGJkXzO4JSduJ8KrNrTIfL1H-IjAMNy1KnDy5hfER4h8fzA8lAoD3y4ZmuWBSlc2nYuVrYc6B0_xGXKqwPaYwapofwJraGuKw4aNcA" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-2">Dezembro
                                        2023</p>
                                    <h4 class="text-xl font-black uppercase italic mb-2">Curitiba Marathon</h4>
                                    <p class="text-slate-500 text-sm mb-4 leading-relaxed">Sua primeira maratona completada
                                        em 03:45:12. Uma performance excepcional sob chuva.</p>
                                    <div class="flex gap-4">
                                        <span class="text-[10px] font-black bg-slate-100 px-3 py-1 rounded uppercase">Pace
                                            5'20"</span>
                                        <span class="text-[10px] font-black bg-slate-100 px-3 py-1 rounded uppercase">Rank
                                            #142</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Rights -->
        <div class="lg:col-span-4">
            <div class="sticky top-32 space-y-8">
                <!-- Perfil -->
                <div class="bg-white border border-slate-100 rounded-3xl p-10 card-shadow">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black uppercase italic tracking-tight">Perfil e Preferências</h3>
                        <button class="text-primary"><span class="material-symbols-outlined">edit_note</span></button>
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
                                    <span class="text-sm font-bold text-primary">Elite Runners</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        class="w-full mt-10 py-4 bg-slate-50 hover:bg-slate-100 text-slate-500 font-black text-[10px] uppercase tracking-[0.2em] rounded-full transition-all">
                        Gerenciar Conta
                    </button>
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
@endsection