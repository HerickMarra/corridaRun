@extends('layouts.app')

@section('content')
    <section class="relative pt-28 pb-16 px-6 lg:px-12 bg-background-light">
        <div
            class="max-w-[1440px] mx-auto overflow-hidden rounded-3xl relative min-h-[600px] lg:min-h-[720px] flex items-center justify-center">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBBxl_-4-yh1caRnhCLD7I4Dg1TWx0pSX_QgNCsjLfcULXS8CgHtyF7TExd8oE1an_iJjvh34hxahnhOEVhTdlXAX4GnR5fEwP4rRkeIAWrsnmAPuBddzDN9h3y-dPh56jzlVIELrr1hphv8ggL3WrylwE2yFeADM-6_poiGKfVqoUOguwfXiZv3KgBSGBFJgPozfMiDJKoMY7fhp0Cl1Fb4QU_GqDLh3AEKDFelFcDJpRS_gV5vyAKdFiqPfLBnprWUx4ioEWv5pA");'>
            </div>
            <div class="absolute inset-0 hero-gradient-overlay"></div>
            <div class="relative z-10 text-center max-w-4xl px-4">
                <span
                    class="inline-block py-2 px-5 bg-white/20 backdrop-blur-md border border-white/30 rounded-full text-white text-[10px] font-bold tracking-[0.2em] uppercase mb-8">
                    Temporada {{ date('Y') }} aberta
                </span>
                <h1
                    class="text-white text-5xl md:text-7xl lg:text-8xl font-black leading-[0.95] tracking-tighter mb-8 uppercase italic">
                    Supere seus <br /><span
                        class="text-white underline decoration-primary decoration-8 underline-offset-8">Limites</span>
                </h1>
                <p class="text-white/90 text-lg md:text-xl font-medium mb-10 max-w-2xl mx-auto leading-relaxed">
                    As maiores maratonas e desafios de trail run do Brasil reunidos em uma plataforma de elite. Performance,
                    tecnologia e paixão.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <button
                        class="bg-primary hover:bg-blue-600 text-white px-10 py-5 rounded-full text-lg font-bold tracking-tight transition-all flex items-center gap-3 shadow-xl shadow-primary/30">
                        Inscreva-se Agora
                        <span class="material-symbols-outlined">trending_flat</span>
                    </button>
                    <button
                        class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/40 px-10 py-5 rounded-full text-lg font-bold tracking-tight transition-all">
                        Ver Calendário
                    </button>
                </div>
            </div>
        </div>
    </section>

    <main class="bg-background-soft">
        <section class="px-6 lg:px-12 pt-16 pb-8">
            <div class="max-w-[1440px] mx-auto">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 border-b border-slate-200 pb-2">
                    <div>
                        <h2 class="text-4xl font-black tracking-tight text-secondary uppercase italic">Próximos Eventos</h2>
                        <p class="text-slate-500 font-medium mt-1">Encontre seu próximo desafio em {{ date('Y') }}</p>
                    </div>
                    <div class="flex gap-8">
                        <a class="flex flex-col items-center justify-center border-b-[3px] border-primary text-secondary pb-[13px] transition-all"
                            href="#">
                            <p class="text-xs font-bold tracking-widest uppercase">Todos</p>
                        </a>
                        <!-- Outros filtros podem ser adicionados aqui -->
                    </div>
                </div>
            </div>
        </section>

        <section class="px-6 lg:px-12 pb-24">
            <div class="max-w-[1440px] mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @forelse($events as $event)
                        <div
                            class="group bg-card-light rounded-2xl overflow-hidden card-shadow hover:card-shadow-hover transition-all duration-300 flex flex-col h-full border border-slate-100">
                            <div class="relative aspect-[16/10] overflow-hidden">
                                <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700"
                                    style='background-image: url("{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' }}");'>
                                </div>
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="bg-secondary text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider">
                                        {{ $event->categories->first()->name ?? 'Evento' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-8 flex flex-col flex-grow">
                                <div class="mb-6">
                                    <h3
                                        class="text-2xl font-black text-secondary mb-2 group-hover:text-primary transition-colors uppercase italic line-clamp-2 break-words">
                                        {{ $event->name }}</h3>
                                    <div
                                        class="flex items-center text-slate-500 gap-2 text-xs font-bold uppercase tracking-widest">
                                        <span class="material-symbols-outlined text-sm text-primary">calendar_today</span>
                                        {{ $event->event_date->translatedFormat('d M') }} • {{ $event->city }},
                                        {{ $event->state }}
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-auto pt-6 border-t border-slate-50">
                                    <div>
                                        <p class="text-slate-400 text-[10px] uppercase font-bold tracking-widest">A partir de
                                        </p>
                                        <p class="text-2xl font-black text-secondary">R$
                                            {{ number_format($event->categories->min('price'), 2, ',', '.') }}</p>
                                    </div>
                                    <a href="/event/{{ $event->slug }}"
                                        class="bg-white border-2 border-secondary hover:bg-secondary hover:text-white text-secondary font-black py-3 px-6 rounded-full text-[10px] uppercase tracking-widest transition-all">
                                        Comprar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <p class="text-slate-500 font-medium">Nenhum evento encontrado no momento.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
@endsection