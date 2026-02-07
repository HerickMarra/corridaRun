<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Endurance Gritty' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Anton&family=Barlow+Condensed:ital,wght@0,400;0,700;0,900;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --accent:
                {{ $content['geral']['accent_color'] ?? '#ff4d00' }}
            ;
        }

        body {
            font-family: 'Barlow Condensed', sans-serif;
            background-color: #0a0a0a;
            color: #e5e5e5;
        }

        .font-anton {
            font-family: 'Anton', sans-serif;
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

        .grainy {
            position: relative;
        }

        .grainy::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("https://www.transparenttextures.com/patterns/stardust.png");
            opacity: 0.1;
            pointer-events: none;
            z-index: 50;
        }

        .shadow-hard {
            box-shadow: 10px 10px 0px var(--accent);
        }

        .clip-slant {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }
    </style>
</head>

<body class="antialiased grainy">
    <!-- Hero -->
    <header class="relative min-h-screen flex items-center justify-center bg-black overflow-hidden clip-slant">
        <div class="absolute inset-0 z-0">
            <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1533167649158-6d508895b680' }}"
                class="w-full h-full object-cover grayscale opacity-50 contrast-125" alt="Ultra Runner">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-transparent to-black opacity-60"></div>
        </div>

        <div class="relative z-10 text-center px-6">
            <p
                class="text-accent font-black italic text-xl md:text-2xl letter-spacing-huge mb-10 skew-x-[-12deg] tracking-[.3em]">
                {{ $content['geral']['tagline'] ?? '100 MILES OF PURE GRIT' }}
            </p>
            <h1 class="font-anton text-8xl md:text-[12rem] leading-none mb-12 uppercase italic relative inline-block">
                {{ $content['geral']['title'] ?? 'BEYOND PAIN' }}
                <div class="absolute -bottom-4 left-0 w-full h-4 bg-accent -z-10 skew-x-[-12deg]"></div>
            </h1>
            <div class="flex flex-col md:flex-row items-center justify-center gap-12 mt-20">
                <a href="#register"
                    class="bg-accent text-black px-12 py-5 font-black text-xl uppercase italic shadow-hard hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all duration-100">
                    {{ $content['geral']['cta_text'] ?? 'ACCEPT CHALLENGE' }}
                </a>
                <div class="text-left max-w-xs border-l-2 border-accent pl-6 bg-black/40 backdrop-blur-sm p-4">
                    <p class="text-[10px] font-black tracking-widest text-zinc-500 uppercase mb-2">Next Brutal Step</p>
                    <p class="font-bold text-lg leading-tight italic">Death Valley Crossing • Summer 2024</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Intro -->
    <section class="py-40 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-24 items-start">
            <div class="md:w-1/2">
                <h2 class="font-anton text-6xl md:text-8xl italic uppercase text-accent mb-12">
                    {{ $content['geral']['intro_title'] ?? 'NOT FOR THE WEAK' }}</h2>
                <p class="text-2xl font-bold italic leading-relaxed text-zinc-400">
                    {{ $content['geral']['intro_text'] ?? 'This isn’t a race. It’s a test of the human spirit. Dust, sweat, and absolute silence.' }}
                </p>
            </div>
            <div class="md:w-1/2 grid grid-cols-1 gap-12">
                <div class="border-t border-zinc-800 pt-12">
                    <h3 class="font-anton text-3xl mb-6 italic">
                        {{ $content['geral']['section_2_title'] ?? 'RESILIENCE' }}</h3>
                    <p class="text-zinc-500 font-bold uppercase tracking-widest leading-loose">Mental fortitude is your
                        only weapon. We provide the survival points, you provide the guts.</p>
                </div>
                <div class="border-t border-zinc-800 pt-12">
                    <h3 class="font-anton text-3xl mb-6 italic">{{ $content['geral']['section_3_title'] ?? 'SURVIVAL' }}
                    </h3>
                    <p class="text-zinc-500 font-bold uppercase tracking-widest leading-loose">No medals for trying.
                        Only for those who cross into the unknown and return.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Grid High Contrast -->
    <section class="py-20 bg-zinc-950">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
            <div class="aspect-square overflow-hidden grayscale contrast-150">
                <img src="https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&q=80&w=600"
                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-700" alt="Detail 1">
            </div>
            <div class="aspect-square overflow-hidden border-x border-zinc-800 group relative">
                <img src="https://images.unsplash.com/photo-1452626038306-9aae5e071dd3?auto=format&fit=crop&q=80&w=600"
                    class="w-full h-full object-cover grayscale contrast-150 group-hover:grayscale-0 transition-all duration-700"
                    alt="Detail 2">
                <div
                    class="absolute inset-0 bg-accent/20 mix-blend-color opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
            </div>
            <div class="aspect-square overflow-hidden grayscale contrast-150">
                <img src="https://images.unsplash.com/photo-1541625602330-2277a1cd1f59?auto=format&fit=crop&q=80&w=600"
                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-700" alt="Detail 3">
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section id="register" class="py-48 px-6 bg-black relative flex flex-col items-center">
        <span
            class="material-symbols-outlined text-[12rem] text-accent/10 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-0">priority_high</span>
        <div class="relative z-10 text-center space-y-12">
            <h2 class="font-anton text-6xl md:text-[10rem] leading-[0.85] italic">STOP MAKING <br>EXCUSES.</h2>
            <div class="pt-8">
                <a href="/register"
                    class="bg-accent text-black px-20 py-8 font-black text-3xl uppercase italic shadow-hard hover:bg-white hover:text-black transition-all">
                    {{ $content['geral']['cta_text'] ?? 'ACCEPT CHALLENGE' }}
                </a>
            </div>
            <p
                class="text-zinc-600 font-black uppercase tracking-[0.5em] text-xs underline underline-offset-8 decoration-accent">
                Only 42 spots left for the elite heat</p>
        </div>
    </section>

    <footer class="py-32 border-t border-zinc-900 bg-black text-center">
        <div class="font-anton text-4xl mb-12 italic tracking-tighter">ENDURANCE<span
                class="text-accent underline">GRITTY</span></div>
        <div
            class="flex flex-wrap justify-center gap-10 text-[10px] font-black uppercase tracking-[0.4em] text-zinc-500 mb-16">
            <a href="#" class="hover:text-accent transition-colors">Survival Protocol</a>
            <a href="#" class="hover:text-accent transition-colors">Death Warrant</a>
            <a href="#" class="hover:text-accent transition-colors">Course Hazards</a>
        </div>
        <p class="text-zinc-800 text-[10px] uppercase font-black tracking-[0.5em]">© 2024 ENDURANCE GRITTY SERIES •
            SURVIVE OR DIE</p>
    </footer>
</body>

</html>