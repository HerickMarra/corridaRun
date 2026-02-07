<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Urban Glow' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;700&family=Syne:wght@400;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #0c0910;
            color: #f8fafc;
        }

        .font-syne {
            font-family: 'Syne', sans-serif;
        }

        .glow-purple {
            box-shadow: 0 0 50px -10px #d946ef;
        }

        .text-neon {
            color: #d946ef;
            text-shadow: 0 0 10px rgba(217, 70, 239, 0.5);
        }

        .bg-neon-gradient {
            background: linear-gradient(135deg, #d946ef 0%, #6366f1 100%);
        }

        .mesh-bg {
            background-image: radial-gradient(at 0% 0%, rgba(217, 70, 239, 0.15) 0, transparent 50%),
                radial-gradient(at 50% 0%, rgba(99, 102, 241, 0.15) 0, transparent 50%);
        }
    </style>
</head>

<body class="antialiased mesh-bg">
    <!-- Navbar -->
    <nav
        class="fixed top-0 w-full z-50 p-6 flex justify-between items-center bg-[#0c0910]/80 backdrop-blur-xl border-b border-white/5">
        <div class="font-syne font-extrabold text-2xl tracking-tighter">URBAN<span class="text-neon">GLOW</span></div>
        <a href="#register"
            class="bg-neon-gradient px-6 py-2 rounded-lg font-bold text-xs uppercase tracking-widest hover:brightness-110 transition-all shadow-lg shadow-purple-500/20">
            {{ $content['geral']['cta_text'] ?? 'JOIN THE NIGHT' }}
        </a>
    </nav>

    <!-- Hero -->
    <header class="relative min-h-screen flex items-center justify-center pt-20">
        <div class="absolute inset-0 z-0 overflow-hidden">
            <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1478720568477-152d9b164e26' }}"
                class="w-full h-full object-cover opacity-30 grayscale-[0.5]" alt="City Night">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0c0910] via-transparent to-transparent"></div>
        </div>

        <div class="relative z-10 text-center px-6">
            <span
                class="inline-block mb-6 px-4 py-1 rounded-full border border-purple-500/30 bg-purple-500/10 text-neon text-[10px] font-bold uppercase tracking-[0.4em]">
                {{ $content['geral']['date_info'] ?? 'SATURDAY NIGHT' }}
            </span>
            <h1
                class="font-syne font-extrabold text-7xl md:text-9xl tracking-tighter leading-none mb-10 decoration-purple-500 underline underline-offset-8">
                {{ $content['geral']['title'] ?? 'CITY LIGHTS RUN' }}
            </h1>
            <p class="text-zinc-400 max-w-xl mx-auto text-lg leading-relaxed mb-12">
                {{ $content['geral']['intro_text'] ?? 'The city belongs to us when the sun goes down. A neon-drenched course through the most iconic streets.' }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#register"
                    class="bg-white text-black px-12 py-4 rounded-xl font-bold text-sm tracking-widest hover:bg-zinc-100 transition-all uppercase">
                    Register Now
                </a>
                <div class="flex items-center gap-2 text-neon text-xs font-bold uppercase tracking-widest px-8">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    Official Race Series
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="py-32 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
            <div class="bg-white/5 border border-white/10 p-12 rounded-[2rem] hover:bg-white/10 transition-all group">
                <div class="size-16 rounded-2xl bg-neon-gradient flex items-center justify-center mb-8 shadow-neon">
                    <span class="material-symbols-outlined text-black font-bold text-3xl">headset</span>
                </div>
                <h3 class="font-syne font-extrabold text-2xl mb-4 group-hover:text-neon transition-colors">
                    {{ $content['geral']['card_1_title'] ?? 'DJ ON TRACK' }}</h3>
                <p class="text-zinc-500 leading-relaxed text-sm">Music at every kilometer to keep your pace and energy
                    through the roof.</p>
            </div>
            <div
                class="bg-white/5 border border-white/10 p-12 rounded-[2rem] hover:bg-white/10 transition-all group relative">
                <div
                    class="absolute -top-4 -right-4 bg-neon-gradient text-black text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter">
                    Exclusivo</div>
                <div class="size-16 rounded-2xl bg-neon-gradient flex items-center justify-center mb-8 shadow-neon">
                    <span class="material-symbols-outlined text-black font-bold text-3xl">stadium</span>
                </div>
                <h3 class="font-syne font-extrabold text-2xl mb-4 group-hover:text-neon transition-colors">
                    {{ $content['geral']['card_2_title'] ?? 'GLOW KITS' }}</h3>
                <p class="text-zinc-500 leading-relaxed text-sm">Neon headbands, reflective bibs, and exclusive light-up
                    medals for all finishers.</p>
            </div>
            <div class="bg-white/5 border border-white/10 p-12 rounded-[2rem] hover:bg-white/10 transition-all group">
                <div class="size-16 rounded-2xl bg-neon-gradient flex items-center justify-center mb-8 shadow-neon">
                    <span class="material-symbols-outlined text-black font-bold text-3xl">celebration</span>
                </div>
                <h3 class="font-syne font-extrabold text-2xl mb-4 group-hover:text-neon transition-colors">
                    {{ $content['geral']['card_3_title'] ?? 'FINISHER PARTY' }}</h3>
                <p class="text-zinc-500 leading-relaxed text-sm">Post-race festival with local craft food, live bands,
                    and recovery zones.</p>
            </div>
        </div>
    </section>

    <!-- Grid Image Section -->
    <section class="py-32 bg-white/5 border-y border-white/5">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-4 h-[600px]">
            <div class="col-span-2 row-span-2 rounded-3xl overflow-hidden relative group">
                <img src="https://images.unsplash.com/photo-1549438343-98282367ea1b?auto=format&fit=crop&q=80&w=800"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                    alt="Urban runner">
                <div class="absolute inset-0 bg-neon-gradient mix-blend-color opacity-40"></div>
            </div>
            <div class="rounded-3xl overflow-hidden group">
                <img src="https://images.unsplash.com/photo-1571008887538-b36bb32f4571?auto=format&fit=crop&q=80&w=400"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                    alt="Neon lights">
            </div>
            <div class="rounded-3xl overflow-hidden group">
                <img src="https://images.unsplash.com/photo-1528605248644-14dd04cb21c7?auto=format&fit=crop&q=80&w=400"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                    alt="City life">
            </div>
            <div class="col-span-2 rounded-3xl overflow-hidden mt-4 group">
                <img src="https://images.unsplash.com/photo-1517404215738-15263e9f9178?auto=format&fit=crop&q=80&w=800"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                    alt="Crowd running">
                <div class="absolute inset-0 bg-blue-500 mix-blend-overlay opacity-20"></div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section id="register" class="py-40 px-6 text-center">
        <div class="max-w-3xl mx-auto space-y-12">
            <h2 class="font-syne font-extrabold text-5xl md:text-8xl tracking-tighter leading-none italic">
                BE THE <span class="text-neon">ENERGY</span> OF THE CITY.
            </h2>
            <div class="p-1 inline-flex bg-white/10 rounded-2xl backdrop-blur-md border border-white/10">
                <a href="/register"
                    class="bg-white text-black px-16 py-6 rounded-xl font-bold text-lg hover:scale-[1.02] transition-all uppercase">
                    {{ $content['geral']['cta_text'] ?? 'OWN THE NIGHT' }}
                </a>
            </div>
            <p class="text-zinc-600 font-bold uppercase tracking-[0.3em] text-[10px]">Over 5,000 runners expected. Don't
                be left in the dark.</p>
        </div>
    </section>

    <footer class="py-20 border-t border-white/5 text-center">
        <div class="font-syne font-extrabold text-xl mb-6">URBAN<span class="text-neon">GLOW</span></div>
        <div class="flex justify-center gap-8 mb-10">
            <a href="#" class="text-zinc-600 hover:text-white transition-colors"><span
                    class="material-symbols-outlined">share</span></a>
            <a href="#" class="text-zinc-600 hover:text-white transition-colors"><span
                    class="material-symbols-outlined">public</span></a>
            <a href="#" class="text-zinc-600 hover:text-white transition-colors"><span
                    class="material-symbols-outlined">mail</span></a>
        </div>
        <p class="text-zinc-700 text-[10px] font-bold uppercase tracking-widest">&copy; 2024 URBAN GLOW CIRCUIT • NEON
            RUNNING CO.</p>
    </footer>
</body>

</html>