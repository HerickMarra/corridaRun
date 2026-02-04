@extends('layouts.app')

@section('title', $event->name)

@section('content')
<main>
    <section class="relative h-[85vh] min-h-[600px] w-full overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[3s] hover:scale-105" 
             style='background-image: url("{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}");'></div>
        <div class="absolute inset-0 hero-gradient" style="background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.4) 100%);"></div>
        <div class="relative h-full max-w-[1440px] mx-auto px-6 lg:px-12 flex flex-col justify-end pb-24">
            <div class="max-w-4xl">
                <span class="bg-primary text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-6 inline-block">
                    {{ $event->categories->first()->name ?? 'Evento' }} • {{ $event->event_date->translatedFormat('F Y') }}
                </span>
                @php 
                    $parts = explode(' ', $event->name);
                    $lastWord = array_pop($parts);
                    $firstName = implode(' ', $parts);
                    $nameLength = strlen($event->name);
                    
                    $fontSizeClass = 'text-5xl md:text-8xl';
                    if ($nameLength > 20) $fontSizeClass = 'text-4xl md:text-7xl';
                    if ($nameLength > 40) $fontSizeClass = 'text-3xl md:text-5xl';
                @endphp
                <h1 class="{{ $fontSizeClass }} font-black text-white uppercase italic leading-[0.9] tracking-tighter mb-8 break-words">
                    {!! $firstName !!} <br/> <span class="text-primary">{!! $lastWord !!}</span>
                </h1>
                <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
                    <a href="#ingressos" class="bg-white text-secondary px-12 py-5 rounded-full text-sm font-black uppercase tracking-[0.2em] hover:bg-primary hover:text-white transition-all duration-300 text-center">
                        Inscreva-se Agora
                    </a>
                    <div class="flex gap-12">
                        <div class="text-white">
                            <p class="text-[10px] font-black uppercase opacity-60 tracking-widest mb-1">Data</p>
                            <p class="text-xl font-bold italic uppercase">{{ $event->event_date->translatedFormat('d.M.Y') }}</p>
                        </div>
                        <div class="text-white">
                            <p class="text-[10px] font-black uppercase opacity-60 tracking-widest mb-1">Local</p>
                            <p class="text-xl font-bold italic uppercase">{{ $event->location }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white px-6 lg:px-12">
        <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-20">
            <div class="lg:col-span-7">
                <h2 class="text-4xl font-black uppercase italic tracking-tighter mb-10">
                    A Prova <span class="text-primary">Mais Icônica</span> da Região
                </h2>
                <div class="prose prose-lg text-slate-600 font-medium leading-relaxed space-y-6">
                    {!! nl2br(e($event->description)) !!}
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8 mt-16 border-t border-slate-100 pt-16">
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-3xl">route</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Distâncias</p>
                            <p class="text-lg font-black italic">
                                {{ $event->categories->pluck('distance')->unique()->implode(' & ') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-3xl">terrain</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Altimetria</p>
                            <p class="text-lg font-black italic">Moderada</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-3xl">water_full</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Hidratação</p>
                            <p class="text-lg font-black italic">Plena</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-5">
                <div class="sticky top-32 space-y-8">
                    <div class="bg-background-soft rounded-3xl p-10 card-shadow" style="box-shadow: 0 10px 40px -15px rgba(0, 0, 0, 0.05);">
                        <h3 class="text-xl font-black uppercase italic tracking-tight mb-8">Informações</h3>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                                <span class="text-sm font-bold text-slate-500 uppercase">Cidade</span>
                                <span class="text-lg font-black italic">{{ $event->city }} / {{ $event->state }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                                <span class="text-sm font-bold text-slate-500 uppercase">Capacidade</span>
                                <span class="text-lg font-black italic text-primary">{{ $event->max_participants }} Atletas</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-500 uppercase">Inscrições</span>
                                <span class="text-lg font-black italic">Abertas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="ingressos" class="py-24 bg-background-soft px-6 lg:px-12">
        <div class="max-w-[1000px] mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black uppercase italic tracking-tighter mb-4">Tipos de <span class="text-primary">Ingresso</span></h2>
                <p class="text-slate-500 font-medium uppercase tracking-[0.2em] text-xs">Selecione sua categoria e kit preferido</p>
            </div>
            
            <div class="space-y-3">
                @foreach($event->categories as $category)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 card-shadow hover:border-primary/30 transition-colors" style="box-shadow: 0 10px 40px -15px rgba(0, 0, 0, 0.05);">
                        <details class="group">
                            <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
                                <div class="flex items-center gap-6">
                                    <div class="bg-slate-50 text-slate-400 size-12 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-primary/5 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined">check_circle</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-black uppercase italic">Kit {{ $category->name }}</h3>
                                        <p class="text-xs text-slate-500 font-medium mt-0.5">Distância: {{ $category->distance }} • Vagas: {{ $category->available_tickets }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-8">
                                    <div class="text-right hidden sm:block">
                                        <p class="text-xl font-black italic text-primary">R$ {{ number_format($category->price, 2, ',', '.') }}</p>
                                    </div>
                                    <span class="material-symbols-outlined transition-transform duration-300 text-slate-400 group-open:rotate-180">expand_more</span>
                                </div>
                            </summary>
                            <div class="px-6 pb-6 pt-2 border-t border-slate-50">
                                    <div class="flex flex-col sm:flex-row items-end sm:items-center justify-between gap-8 py-2">
                                        @if(!empty($category->items_included))
                                            <div class="space-y-4 w-full">
                                                <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400">O que está incluso:</h4>
                                                <ul class="grid grid-cols-1 gap-2">
                                                    @foreach($category->items_included as $item)
                                                        <li class="flex items-center gap-2 text-sm font-medium text-slate-600"><span class="size-1.5 bg-primary rounded-full"></span> {{ $item }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div></div> <!-- Spacer for alignment -->
                                        @endif
                                        <div class="flex flex-col items-end gap-4 flex-shrink-0">
                                            <div class="text-right sm:hidden">
                                                <p class="text-2xl font-black italic">R$ {{ number_format($category->price, 2, ',', '.') }}</p>
                                            </div>
                                            <a href="{{ route('checkout.index', ['category' => $category->id, 'kit' => request('kit')]) }}" class="w-full sm:w-auto px-12 py-4 bg-primary text-white font-black uppercase text-[10px] tracking-[0.2em] rounded-full hover:bg-black transition-all text-center">
                                                Selecionar Ingresso
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="w-full relative h-[600px] overflow-hidden bg-slate-100">
        <div class="absolute inset-0 z-0">
            <iframe allowfullscreen="" height="100%" loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14628.272101036836!2d-46.6668!3d-23.5475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce5833440789d5%3A0x6779a572a129d271!2sEst%C3%A1dio%20do%20Pacaembu!5e0!3m2!1sen!2sbr!4v1625480000000!5m2!1sen!2sbr" style="border:0; filter: grayscale(1) invert(0.1) contrast(1.1);" width="100%">
            </iframe>
        </div>
        <div class="absolute top-12 left-6 lg:left-12 z-10 bg-white p-8 rounded-3xl shadow-2xl max-w-sm border border-slate-100">
            <h4 class="text-xl font-black uppercase italic mb-4">Localização</h4>
            <p class="text-sm text-slate-500 font-medium mb-6 leading-relaxed">
                {{ $event->location }}<br/>
                {{ $event->city }}, {{ $event->state }}
            </p>
            <div class="flex items-center gap-4 text-primary font-black text-[10px] uppercase tracking-widest cursor-pointer hover:underline">
                <span class="material-symbols-outlined">map</span>
                Abrir no Google Maps
            </div>
        </div>
    </section>
</main>
@endsection
