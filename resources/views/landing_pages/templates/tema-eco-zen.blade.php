<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Eco-Zen Run' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;700&family=Fraunces:ital,wght@1,300;1,600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --accent:
                {{ $content['geral']['accent_color'] ?? '#2d6a4f' }}
            ;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #f7f3ee;
            color: #1b4332;
        }

        .font-fraunces {
            font-family: 'Fraunces', serif;
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

        .zen-gradient {
            background: linear-gradient(to bottom, rgba(247, 243, 238, 0) 0%, rgba(247, 243, 238, 1) 100%);
        }
    </style>
</head>

<body class="antialiased">
    <!-- Hero -->
    <header class="relative min-h-screen flex items-center justify-center pt-20">
        <div class="absolute inset-0 z-0">
            <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1544717297-fa95b8ee4bc1' }}"
                class="w-full h-full object-cover" alt="Nature Run">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 zen-gradient"></div>
        </div>

        <div class="relative z-10 text-center max-w-4xl px-6">
            <h1 class="font-fraunces italic text-7xl md:text-9xl tracking-tight mb-12">
                {{ $content['geral']['title'] ?? 'RUN IN HARMONY' }}
            </h1>
            <div class="flex justify-center mb-12">
                <div
                    class="size-16 rounded-full border border-accent/20 flex items-center justify-center animate-bounce">
                    <span class="material-symbols-outlined text-accent">keyboard_arrow_down</span>
                </div>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-accent/60">Sustainability • Wellness •
                Performance</p>
        </div>
    </header>

    <!-- Intro Text -->
    <section class="py-32 px-6">
        <div class="max-w-3xl mx-auto text-center space-y-12">
            <span class="material-symbols-outlined text-5xl text-accent">eco</span>
            <p class="font-fraunces italic text-3xl md:text-4xl text-accent leading-snug">
                {{ $content['geral']['intro_text'] ?? 'Discover the balance between physical effort and mental clarity. Our eco-conscious events focus on sustainable practices.' }}
            </p>
            <div class="h-px w-20 bg-accent/20 mx-auto"></div>
        </div>
    </section>

    <!-- Pillars -->
    <section class="py-32 px-6 bg-[#ede8e1]">
        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-16 text-center">
            <div class="space-y-8">
                <div class="size-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm">
                    <span class="material-symbols-outlined text-accent text-3xl">psychology</span>
                </div>
                <h3 class="font-fraunces italic text-2xl uppercase tracking-widest text-accent">
                    {{ $content['geral']['pillar_1'] ?? 'Sustainable Pace' }}</h3>
                <p class="text-zinc-500 text-sm leading-relaxed">Focusing on long-term health and consistent growth over
                    short-term gains.</p>
            </div>
            <div class="space-y-8">
                <div class="size-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm">
                    <span class="material-symbols-outlined text-accent text-3xl">spa</span>
                </div>
                <h3 class="font-fraunces italic text-2xl uppercase tracking-widest text-accent">
                    {{ $content['geral']['pillar_2'] ?? 'Mental Clarity' }}</h3>
                <p class="text-zinc-500 text-sm leading-relaxed">Integrated meditation zones at start and finish for
                    total mind-body connection.</p>
            </div>
            <div class="space-y-8">
                <div class="size-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm">
                    <span class="material-symbols-outlined text-accent text-3xl">recycling</span>
                </div>
                <h3 class="font-fraunces italic text-2xl uppercase tracking-widest text-accent">
                    {{ $content['geral']['pillar_3'] ?? 'Zero Waste Event' }}</h3>
                <p class="text-zinc-500 text-sm leading-relaxed">Single-use plastic free course with organic catering
                    from local farmers.</p>
            </div>
        </div>
    </section>

    <!-- Scenic View -->
    <section class="relative h-[80vh]">
        <img src="https://images.unsplash.com/photo-1547483732-2ab8272d7c97?auto=format&fit=crop&q=80&w=1920"
            class="w-full h-full object-cover" alt="Scenic Path">
        <div class="absolute inset-0 bg-accent/30 mix-blend-multiply"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-white/90 backdrop-blur-md p-12 max-w-lg text-center rounded-2xl shadow-xl">
                <h4 class="font-fraunces italic text-3xl mb-4 italic">Reconnect with your roots</h4>
                <p class="text-zinc-600 text-sm mb-8">Every registration plants a tree in our reforestation project.</p>
                <div
                    class="font-bold text-accent text-xs uppercase tracking-widest flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">verified</span>
                    Eco-Certified Series
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-48 px-6 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="font-fraunces italic text-5xl md:text-7xl mb-12">Peace in every stride.</h2>
            <a href="/register"
                class="inline-block bg-accent text-white px-16 py-6 rounded-full font-bold text-sm tracking-widest hover:brightness-110 transition-all uppercase shadow-lg shadow-emerald-900/10">
                {{ $content['geral']['cta_text'] ?? 'FIND YOUR FLOW' }}
            </a>
            <p class="mt-12 text-zinc-400 text-[10px] font-bold uppercase tracking-widest">Limited invitation-only
                wellness run</p>
        </div>
    </section>

    <footer class="py-24 border-t border-accent/10">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <div class="italic font-fraunces text-2xl text-accent mb-4">EcoZen Circuit</div>
                <p class="text-zinc-400 text-xs">A platform dedicated to conscious athletic performance.</p>
            </div>
            <div class="flex md:justify-end gap-12 text-[10px] font-black uppercase tracking-widest text-accent/40">
                <a href="#" class="hover:text-accent transition-colors">Philosophy</a>
                <a href="#" class="hover:text-accent transition-colors">Locations</a>
                <a href="#" class="hover:text-accent transition-colors">Manifesto</a>
            </div>
        </div>
        <div
            class="max-w-7xl mx-auto px-6 mt-20 pt-10 border-t border-accent/5 text-[10px] uppercase font-bold text-zinc-300 tracking-[0.4em] text-center">
            © 2024 ECO-ZEN RUNNING INITIATIVE • PLANET FIRST
        </div>
    </footer>
</body>

</html>