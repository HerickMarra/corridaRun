@extends('layouts.app')

@section('content')
    <main class="pt-40 pb-32 px-6 lg:px-12 bg-background-soft">
        <div class="max-w-[1440px] mx-auto">
            {{-- Header --}}
            <section class="mb-16 text-center">
                <span class="text-primary font-black uppercase tracking-[0.3em] text-[10px] mb-3 block">Temporada
                    {{ date('Y') }}</span>
                <h1 class="text-5xl md:text-6xl font-black text-secondary uppercase italic leading-[0.95] tracking-tighter">
                    Calendário de <span class="text-primary">Corridas</span>
                </h1>
                <p class="mt-4 text-slate-500 font-medium text-lg max-w-2xl mx-auto">
                    Planeje sua temporada com os melhores eventos de corrida do Brasil
                </p>
            </section>

            {{-- Filters --}}
            <section class="mb-12">
                <form method="GET" action="{{ route('calendar') }}"
                    class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-2">Mês</label>
                            <select name="month"
                                class="border-slate-200 rounded-lg text-sm font-medium py-3 px-4 focus:border-primary focus:ring-primary">
                                <option value="">Todos os Meses</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-primary text-white px-8 py-3 rounded-lg font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all">
                                Filtrar Eventos
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            {{-- Events by Month --}}
            @forelse($eventsByMonth as $monthKey => $events)
                @php
                    $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthKey);
                @endphp

                <section class="mb-20">
                    {{-- Month Header --}}
                    <div class="flex items-center gap-6 mb-10">
                        <div class="flex-shrink-0 text-center">
                            <div class="bg-gradient-to-br from-primary to-blue-600 text-white rounded-2xl p-6 shadow-xl">
                                <p class="text-4xl font-black italic leading-none">{{ $monthDate->format('m') }}</p>
                                <p class="text-xs font-black uppercase tracking-widest mt-1">
                                    {{ $monthDate->translatedFormat('M') }}</p>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <h2 class="text-3xl font-black uppercase italic text-secondary">
                                {{ $monthDate->translatedFormat('F') }} {{ $monthDate->format('Y') }}</h2>
                            <p class="text-slate-500 font-medium">{{ $events->count() }}
                                {{ $events->count() == 1 ? 'evento' : 'eventos' }}
                                programado{{ $events->count() == 1 ? '' : 's' }}</p>
                        </div>
                        <div class="hidden md:block flex-grow border-t-2 border-slate-100"></div>
                    </div>

                    {{-- Events Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($events as $event)
                            <div
                                class="group bg-white rounded-2xl overflow-hidden card-shadow hover:card-shadow-hover transition-all duration-300 flex flex-col h-full border border-slate-100">
                                {{-- Event Image --}}
                                <div class="relative aspect-[16/10] overflow-hidden">
                                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700"
                                        style='background-image: url("{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}");'>
                                    </div>
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="bg-secondary text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider">
                                            {{ $event->categories->first()->name ?? 'Evento' }}
                                        </span>
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-xl px-3 py-2 text-center">
                                        <p class="text-2xl font-black italic text-secondary leading-none">
                                            {{ $event->event_date->format('d') }}</p>
                                        <p class="text-[8px] font-black uppercase text-slate-500 tracking-wider">
                                            {{ $event->event_date->translatedFormat('M') }}</p>
                                    </div>
                                </div>

                                {{-- Event Details --}}
                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="mb-4">
                                        <h3
                                            class="text-xl font-black text-secondary mb-2 group-hover:text-primary transition-colors uppercase italic line-clamp-2">
                                            {{ $event->name }}
                                        </h3>
                                        <div
                                            class="flex items-center text-slate-500 gap-2 text-xs font-bold uppercase tracking-widest mb-2">
                                            <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                                            {{ $event->city }}, {{ $event->state }}
                                        </div>
                                        <div
                                            class="flex items-center text-slate-500 gap-2 text-xs font-bold uppercase tracking-widest">
                                            <span class="material-symbols-outlined text-sm text-primary">calendar_today</span>
                                            {{ $event->event_date->translatedFormat('d \d\e F \d\e Y') }}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-50">
                                        <div>
                                            <p class="text-slate-400 text-[10px] uppercase font-bold tracking-widest">A partir de
                                            </p>
                                            <p class="text-2xl font-black text-secondary">R$
                                                {{ number_format($event->categories->min('price'), 2, ',', '.') }}</p>
                                        </div>
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="bg-white border-2 border-secondary hover:bg-secondary hover:text-white text-secondary font-black py-3 px-6 rounded-full text-[10px] uppercase tracking-widest transition-all">
                                            Ver Mais
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="text-center py-20">
                    <div class="bg-white rounded-3xl p-16 border-2 border-dashed border-slate-200">
                        <span class="material-symbols-outlined text-slate-300 text-6xl mb-4">event_busy</span>
                        <h3 class="text-2xl font-black text-slate-400 uppercase italic mb-2">Nenhum Evento Encontrado</h3>
                        <p class="text-slate-500 font-medium mb-6">Não há eventos programados com os filtros selecionados.</p>
                        <a href="{{ route('calendar') }}"
                            class="inline-block bg-primary text-white px-8 py-3 rounded-full font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all">
                            Limpar Filtros
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </main>
@endsection