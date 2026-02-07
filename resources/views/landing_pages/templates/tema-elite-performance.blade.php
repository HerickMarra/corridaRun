<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Elite Performance' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Syncopate:wght@400;700&family=Inter:wght@400;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --accent:
                {{ $content['geral']['accent_color'] ?? '#00f2ff' }}
            ;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #050505;
            color: white;
        }

        .font-sync {
            font-family: 'Syncopate', sans-serif;
        }

        .text-accent {
            color: var(--accent);
        }

        .bg-accent {
            background-color: var(--accent);
        }

        .border-accent {
            border-color: var(--accent);
        }

        .glow-text {
            text-shadow: 0 0 20px var(--accent);
        }

        .hero-gradient {
            background: linear-gradient(to bottom, rgba(5, 5, 5, 0.2) 0%, rgba(5, 5, 5, 1) 100%);
        }
    </style>
</head>

<body class="antialiased">
    <!-- Nav -->
    <nav class="fixed w-full z-50 px-6 py-6 flex justify-between items-center backdrop-blur-md border-b border-white/5">
        <div class="font-sync font-bold text-xl tracking-tighter">ELITE<span class="text-accent">PERF</span></div>
        <a href="#register"
            class="bg-accent text-black px-6 py-2 rounded-full font-black text-xs uppercase tracking-widest hover:scale-105 transition-transform">
            {{ $content['geral']['cta_text'] ?? 'JOIN NOW' }}
        </a>
    </nav>

    <!-- Hero -->
    <header class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}"
                class="w-full h-full object-cover opacity-40 scale-105" alt="Elite Runner">
        </div>
        <div class="absolute inset-0 z-10 hero-gradient"></div>

        <div class="relative z-20 text-center px-6 mt-20">
            <p class="text-accent font-sync uppercase tracking-[0.5em] text-xs mb-4 glow-text">
                {{ $content['geral']['subtitle'] ?? 'THE ULTIMATE SPEED CHALLENGE' }}
            </p>
            <h1 class="font-sync font-bold text-6xl md:text-8xl lg:text-9xl tracking-tighter mb-8 italic">
                {!! str_replace(' ', '<br class="hidden md:block">', $content['geral']['title'] ?? 'BREAK LIMITS') !!}
            </h1>
            <div class="flex flex-col md:flex-row items-center justify-center gap-6 mt-12">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-accent">schedule</span>
                    <span class="text-xs font-bold tracking-widest uppercase italic">Midnight Start</span>
                </div>
                <div class="w-12 h-px bg-white/20 hidden md:block"></div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-accent">location_on</span>
                    <span class="text-xs font-bold tracking-widest uppercase italic">Downtown Circuit</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Intro -->
    <section class="py-32 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-20 items-center">
            <div class="border-l-4 border-accent pl-8">
                <h2 class="font-sync text-4xl mb-8 uppercase italic">
                    {{ $content['geral']['intro_title'] ?? 'ENGINEERED FOR SPEED' }}</h2>
                <p class="text-zinc-400 text-lg leading-relaxed font-light">
                    {{ $content['geral']['intro_text'] ?? 'Join the elite group of runners at the midnight sprint. A flat, fast course designed for personal bests.' }}
                </p>
            </div>
            <div class="relative group">
                <div class="absolute -inset-1 bg-accent/20 rounded-2xl blur group-hover:bg-accent/40 transition-all">
                </div>
                <div class="relative bg-zinc-900 overflow-hidden rounded-2xl aspect-video">
                    <img src="https://images.unsplash.com/photo-1452626038306-9aae5e071dd3?auto=format&fit=crop&q=80&w=800"
                        class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700"
                        alt="Action Shot">
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-32 bg-zinc-900/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-12">
                <div class="p-10 border border-white/5 hover:border-accent/30 transition-colors bg-black/40">
                    <span class="material-symbols-outlined text-accent text-4xl mb-6">timer</span>
                    <h4 class="font-sync text-lg mb-4">{{ $content['geral']['feat_1_title'] ?? 'CHRONO TECH' }}</h4>
                    <p class="text-zinc-500 text-sm leading-relaxed">
                        {{ $content['geral']['feat_1_desc'] ?? 'Extreme precision timing for every stride.' }}
                    </p>
                </div>
                <div class="p-10 border border-white/5 hover:border-accent/30 transition-colors bg-black/40 relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-accent"></div>
                    <span class="material-symbols-outlined text-accent text-4xl mb-6">bolt</span>
                    <h4 class="font-sync text-lg mb-4">{{ $content['geral']['feat_2_title'] ?? 'NITRO STATION' }}</h4>
                    <p class="text-zinc-500 text-sm leading-relaxed">
                        {{ $content['geral']['feat_2_desc'] ?? 'Premium hydration and energy pods.' }}
                    </p>
                </div>
                <div class="p-10 border border-white/5 hover:border-accent/30 transition-colors bg-black/40">
                    <span class="material-symbols-outlined text-accent text-4xl mb-6">light_mode</span>
                    <h4 class="font-sync text-lg mb-4">{{ $content['geral']['feat_3_title'] ?? 'GLOW TRACK' }}</h4>
                    <p class="text-zinc-500 text-sm leading-relaxed">
                        {{ $content['geral']['feat_3_desc'] ?? 'Fully illuminated high-performance asphalt.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="register" class="py-32 px-6 text-center">
        <div class="max-w-4xl mx-auto py-20 bg-accent text-black rounded-[3rem] shadow-[0_0_50px_rgba(0,242,255,0.3)]">
            <h2 class="font-sync text-4xl md:text-6xl mb-8 uppercase italic font-black">READY TO FLY?</h2>
            <p class="font-bold uppercase tracking-widest text-sm mb-12 opacity-70">LIMITED SLOTS AVAILABLE FOR ELITE
                CATEGORY</p>
            <a href="/register"
                class="bg-black text-white px-12 py-5 rounded-full font-sync text-lg hover:scale-105 hover:bg-zinc-800 transition-all inline-block">
                {{ $content['geral']['cta_text'] ?? 'SECURE YOUR SLOT' }}
            </a>
        </div>
    </section>

    <footer class="py-20 border-t border-white/5 text-center">
        <div class="font-sync font-bold text-xl mb-8">ELITE<span class="text-accent">PERF</span></div>
        <p class="text-zinc-600 text-[10px] uppercase font-bold tracking-[0.3em]">&copy; 2024 ELITE PERFORMANCE CIRCUIT®
        </p>
    </footer>
</body>

</html>