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
                                @if($event->status === \App\Enums\EventStatus::Closed)
                                    <span class="text-lg font-black italic text-amber-500 uppercase">Encerradas</span>
                                @elseif($event->status === \App\Enums\EventStatus::Cancelled)
                                    <span class="text-lg font-black italic text-red-500 uppercase">Canceladas</span>
                                @elseif($event->registration_end < now())
                                    <span class="text-lg font-black italic text-red-500 uppercase">Encerradas</span>
                                @elseif($event->categories->sum('available_tickets') <= 0)
                                    <span class="text-lg font-black italic text-orange-500 uppercase">Esgotadas</span>
                                @else
                                    <span class="text-lg font-black italic text-green-500 uppercase">Abertas</span>
                                @endif
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
                    @php
                        $isSoldOut = $category->available_tickets <= 0;
                        $isExpired = $event->registration_end < now();
                        $isBlocked = in_array($event->status->value, ['closed', 'cancelled']);
                        $isDisabled = $isSoldOut || $isExpired || $isBlocked;
                    @endphp
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 card-shadow transition-all {{ $isDisabled ? 'opacity-75 grayscale-[0.5]' : 'hover:border-primary/30' }}" style="box-shadow: 0 10px 40px -15px rgba(0, 0, 0, 0.05);">
                        <details class="group" {{ $isDisabled ? 'disabled' : '' }}>
                            <summary class="flex items-center justify-between p-6 {{ $isDisabled ? 'cursor-not-allowed' : 'cursor-pointer' }} list-none">
                                <div class="flex items-center gap-6">
                                    <div class="bg-slate-50 {{ $isDisabled ? 'text-slate-300' : 'text-slate-400 group-hover:bg-primary/5 group-hover:text-primary' }} size-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors">
                                        <span class="material-symbols-outlined">{{ $isSoldOut ? 'block' : ($isExpired ? 'timer_off' : 'check_circle') }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-black uppercase italic {{ $isDisabled ? 'text-slate-400' : '' }}">{{ $category->name }}</h3>
                                        @if($isBlocked)
                                            <p class="text-[10px] text-amber-600 font-black uppercase tracking-widest mt-0.5">Indisponível (Evento {{ $event->status === \App\Enums\EventStatus::Closed ? 'Encerrado' : 'Cancelado' }})</p>
                                        @elseif($isSoldOut)
                                            <p class="text-[10px] text-orange-500 font-black uppercase tracking-widest mt-0.5">Vagas Esgotadas</p>
                                        @elseif($isExpired)
                                            <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-0.5">Inscrições Encerradas</p>
                                        @else
                                            <p class="text-xs text-slate-500 font-medium mt-0.5">Distância: {{ $category->distance }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-8">
                                    <div class="text-right hidden sm:block">
                                        <p class="text-xl font-black italic {{ $isDisabled ? 'text-slate-300' : 'text-primary' }}">R$ {{ number_format($category->price, 2, ',', '.') }}</p>
                                    </div>
                                    @if(!$isDisabled)
                                        <span class="material-symbols-outlined transition-transform duration-300 text-slate-400 group-open:rotate-180">expand_more</span>
                                    @endif
                                </div>
                            </summary>
                            @if(!$isDisabled)
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
                            @endif
                        </details>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if($event->routes->count() > 0)
        <!-- Mapa de Percurso -->
        <section class="max-w-7xl mx-auto px-4 py-20 border-t border-slate-100">
            <div class="mb-12 text-center md:text-left">
                <span class="text-primary font-black uppercase text-[10px] tracking-[0.3em] mb-4 block">Percurso</span>
                <h2 class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter">Onde a mágica <span class="text-primary">Acontece</span></h2>
            </div>

            <div class="grid lg:grid-cols-4 gap-12">
                <div class="lg:col-span-1 space-y-4">
                    <p class="text-sm text-slate-500 font-medium leading-relaxed mb-8">
                        Confira o traçado oficial da prova. Abaixo você vê todas as distâncias e seus respectivos caminhos.
                    </p>
                    
                    <div class="flex flex-col gap-3">
                        @foreach($event->routes as $index => $route)
                            <div class="route-selector p-4 rounded-2xl bg-white border border-slate-100 flex items-center gap-3 cursor-pointer hover:border-primary/30 transition-all group" 
                                 data-index="{{ $index }}" 
                                 onclick="switchRoute({{ $index }})">
                                <div class="size-3 rounded-full flex-shrink-0" style="background-color: {{ $route->color }}"></div>
                                <span class="font-black uppercase italic text-xs tracking-wider text-slate-700 group-hover:text-primary transition-colors">{{ $route->name }}</span>
                                <span class="material-symbols-outlined ml-auto text-slate-300 group-hover:text-primary text-sm opacity-0 group-hover:opacity-100 transition-all">chevron_right</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 p-6 rounded-2xl bg-primary/5 border border-primary/10">
                        <div class="flex items-center gap-4 text-primary font-black text-[10px] uppercase tracking-widest cursor-pointer hover:underline" onclick="window.open('https://www.google.com/maps/search/?api=1&query={{ urlencode($event->location . ' ' . $event->city) }}')">
                            <span class="material-symbols-outlined">directions_run</span>
                            Como chegar na largada
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <div class="rounded-3xl overflow-hidden shadow-2xl shadow-slate-200 border-4 border-white h-[500px] md:h-[600px] relative">
                        <div id="route-map" class="w-full h-full"></div>
                    </div>
                </div>
            </div>
        </section>
    @endif
 
     @if($event->regulation)
         <!-- Regulamento da Prova -->
         <section class="max-w-7xl mx-auto px-4 py-20 border-t border-slate-100">
             <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden group">
                 <details class="group">
                     <summary class="flex items-center justify-between p-10 cursor-pointer list-none bg-white hover:bg-slate-50/50 transition-colors">
                         <div class="flex items-center gap-8">
                             <div class="size-16 rounded-3xl bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-500 shadow-lg shadow-primary/5">
                                 <span class="material-symbols-outlined text-3xl">description</span>
                             </div>
                             <div>
                                 <h3 class="text-2xl font-black uppercase italic tracking-tighter">Regulamento <span class="text-primary">Oficial</span></h3>
                                 <p class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mt-1 flex items-center gap-2">
                                     <span class="size-1.5 bg-primary rounded-full animate-pulse"></span>
                                     Clique para expandir as regras reais da prova
                                 </p>
                             </div>
                         </div>
                         <div class="size-12 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 group-open:rotate-180 group-open:bg-primary group-open:text-white group-open:border-primary transition-all duration-500">
                             <span class="material-symbols-outlined">expand_more</span>
                         </div>
                     </summary>
                     <div class="px-10 pb-16 pt-10 border-t border-slate-50 bg-slate-50/30">
                         <div class="prose prose-slate prose-lg max-w-none 
                             prose-headings:font-black prose-headings:uppercase prose-headings:italic prose-headings:tracking-tighter prose-headings:text-slate-900
                             prose-p:text-slate-600 prose-p:font-medium prose-p:leading-relaxed
                             prose-li:text-slate-600 prose-li:font-medium
                             prose-strong:text-slate-900 prose-strong:font-black
                             prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                             prose-img:rounded-[32px] prose-img:shadow-2xl prose-img:border-4 prose-img:border-white">
                             {!! $event->regulation !!}
                         </div>
                     </div>
                 </details>
             </div>
         </section>
     @endif

    @push('scripts')
    @if($event->routes->count() > 0)
    <script>
        let map;
        let routeObjects = [];
        let activeRouteIndex = 0;

        function initRouteMap() {
            const routes = @json($event->routes);
            map = new google.maps.Map(document.getElementById("route-map"), {
                zoom: 14,
                center: { lat: -15.7942, lng: -47.8822 },
                mapId: 'RUNPACE_PUBLIC_MAP',
                disableDefaultUI: false,
                scrollwheel: false,
            });

            routes.forEach((route, index) => {
                const path = Array.isArray(route.path) ? route.path : JSON.parse(route.path);
                if (path && path.length > 0) {
                    const polyline = new google.maps.Polyline({
                        path: path,
                        strokeColor: route.color || "#0d59f2",
                        strokeOpacity: 0.8,
                        strokeWeight: 6,
                        map: null, // Hidden by default
                        clickable: false
                    });

                    const markerObjects = [];
                    const markers = route.markers || {};
                    Object.keys(markers).forEach(type => {
                        if (markers[type]) {
                            let iconColor, symbol, isSymbol = false;
                            if (type === 'start') { iconColor = "#22c55e"; symbol = "1"; }
                            if (type === 'end') { iconColor = "#ef4444"; symbol = "2"; }
                            if (type === 'both') { iconColor = "#0d59f2"; symbol = "flag_circle"; isSymbol = true; }

                            markerObjects.push(new google.maps.Marker({
                                position: markers[type],
                                map: null, // Hidden by default
                                label: {
                                    text: symbol,
                                    fontFamily: isSymbol ? "'Material Symbols Outlined'" : "Inter, sans-serif",
                                    color: "#ffffff",
                                    fontSize: isSymbol ? "18px" : "14px",
                                    fontWeight: isSymbol ? "normal" : "900"
                                },
                                icon: {
                                    path: "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z",
                                    fillColor: iconColor,
                                    fillOpacity: 1,
                                    strokeWeight: 2,
                                    strokeColor: "#ffffff",
                                    scale: 1.8,
                                    anchor: new google.maps.Point(12, 22),
                                    labelOrigin: new google.maps.Point(12, 9)
                                }
                            }));
                        }
                    });

                    const bounds = new google.maps.LatLngBounds();
                    path.forEach(point => bounds.extend(point));
                    // Also extend bounds to include markers if any
                    Object.values(markers).forEach(m => { if(m) bounds.extend(m); });

                    routeObjects.push({
                        polyline: polyline,
                        markerObjects: markerObjects,
                        bounds: bounds,
                        path: path
                    });
                }
            });

            // Select first route by default
            if (routeObjects.length > 0) {
                switchRoute(0);
            }
        }

        function switchRoute(index) {
            if (!routeObjects[index]) return;

            // Update Map
            routeObjects.forEach((obj, idx) => {
                if (idx === index) {
                    obj.polyline.setMap(map);
                    obj.markerObjects.forEach(m => m.setMap(map));
                    map.fitBounds(obj.bounds);
                    
                    // Add some padding to bounds
                    setTimeout(() => {
                        const currentZoom = map.getZoom();
                        if (currentZoom > 16) map.setZoom(16);
                    }, 50);
                } else {
                    obj.polyline.setMap(null);
                    obj.markerObjects.forEach(m => m.setMap(null));
                }
            });

            // Update UI list
            document.querySelectorAll('.route-selector').forEach((el, idx) => {
                if (idx === index) {
                    el.classList.add('border-primary', 'bg-primary/5', 'ring-2', 'ring-primary/10');
                    el.classList.remove('bg-white', 'border-slate-100');
                } else {
                    el.classList.remove('border-primary', 'bg-primary/5', 'ring-2', 'ring-primary/10');
                    el.classList.add('bg-white', 'border-slate-100');
                }
            });

            activeRouteIndex = index;
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initRouteMap" async defer></script>
    @endif
    @endpush
</main>
@endsection
