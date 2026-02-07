<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Family Play Run' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&family=Jost:wght@400;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --primary:
                {{ $content['geral']['primary_color'] ?? '#4cc9f0' }}
            ;
            --secondary: #f72585;
            --accent: #fee440;
        }

        body {
            font-family: 'Jost', sans-serif;
            background-color: #fff;
            color: #2b2d42;
        }

        .text-primary {
            color: var(--primary);
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .text-secondary {
            color: var(--secondary);
        }

        .bg-secondary {
            background-color: var(--secondary);
        }

        .bg-accent {
            background-color: var(--accent);
        }

        .blob-bg {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        }

        .comic {
            font-family: 'Comic Neue', cursive;
        }

        .border-dashed-custom {
            border-style: dashed;
            border-width: 4px;
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden">
    <!-- Header -->
    <header class="relative py-20 px-6 overflow-hidden bg-zinc-50">
        <!-- Floating Shapes -->
        <div class="absolute top-10 -left-20 size-64 bg-primary/10 rounded-full blur-3xl -z-0"></div>
        <div class="absolute bottom-10 -right-20 size-96 bg-secondary/10 rounded-full blur-3xl -z-0"></div>

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16 relative z-10">
            <div class="lg:w-1/2 space-y-8">
                <div
                    class="inline-flex items-center gap-2 bg-accent px-4 py-2 rounded-full font-black text-xs uppercase tracking-widest comic">
                    <span class="material-symbols-outlined text-[18px]">sentiment_very_satisfied</span>
                    {{ $content['geral']['tagline'] ?? 'FUN FOR ALL AGES' }}
                </div>
                <h1 class="text-7xl md:text-9xl font-black leading-[0.8] tracking-tighter text-zinc-900 uppercase">
                    {{ $content['geral']['title'] ?? 'KIDS & FAMILY RUN' }}
                </h1>
                <p class="text-xl font-medium text-zinc-500 max-w-lg leading-relaxed">
                    {{ $content['geral']['intro_text'] ?? 'A day of laughter, movement, and medals for everyone.' }}
                </p>
                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="#register"
                        class="bg-secondary text-white px-10 py-5 rounded-3xl font-black text-lg hover:scale-105 transition-all shadow-xl shadow-pink-500/20 uppercase comic">
                        {{ $content['geral']['cta_text'] ?? 'REGISTER NOW' }}
                    </a>
                    <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-3xl border border-zinc-100 shadow-sm">
                        <div class="size-12 bg-primary rounded-2xl flex items-center justify-center text-white">
                            <span class="material-symbols-outlined">local_activity</span>
                        </div>
                        <span class="font-bold text-sm">Tickets from $15</span>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 relative">
                <div
                    class="aspect-square bg-white border-dashed-custom border-primary p-6 rounded-[4rem] rotate-3 relative overflow-hidden group">
                    <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1541625602330-2277a1cd1f59' }}"
                        class="w-full h-full object-cover rounded-[3rem] group-hover:scale-110 transition-transform duration-700"
                        alt="Family Running">
                </div>
                <!-- Mini Sticker -->
                <div
                    class="absolute -top-10 -right-10 size-32 bg-accent rounded-full flex flex-col items-center justify-center rotate-12 shadow-lg border-4 border-white">
                    <span class="font-black text-xs uppercase comic">Free</span>
                    <span class="font-black text-lg uppercase comic">Ice Cream</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Features -->
    <section class="py-32 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-12 text-center">
            <div class="space-y-6 flex-1">
                <div class="size-20 bg-primary/10 rounded-3xl flex items-center justify-center mx-auto text-primary">
                    <span class="material-symbols-outlined text-4xl">styler</span>
                </div>
                <h3 class="font-black text-2xl uppercase tracking-tighter">
                    {{ $content['geral']['attr_1'] ?? 'Costume Contest' }}</h3>
                <p class="text-zinc-500 font-medium text-sm">Dress up as your favorite hero and win special prizes.</p>
            </div>
            <div class="space-y-6 flex-1 pt-12">
                <div
                    class="size-20 bg-secondary/10 rounded-3xl flex items-center justify-center mx-auto text-secondary">
                    <span class="material-symbols-outlined text-4xl">castle</span>
                </div>
                <h3 class="font-black text-2xl uppercase tracking-tighter">
                    {{ $content['geral']['attr_2'] ?? 'Bouncy Castle' }}</h3>
                <p class="text-zinc-500 font-medium text-sm">Post-race fun zone with inflatables for all kids.</p>
            </div>
            <div class="space-y-6 flex-1">
                <div class="size-20 bg-accent/20 rounded-3xl flex items-center justify-center mx-auto text-zinc-800">
                    <span class="material-symbols-outlined text-4xl">cookie</span>
                </div>
                <h3 class="font-black text-2xl uppercase tracking-tighter">
                    {{ $content['geral']['attr_3'] ?? 'Healthy Snacks' }}</h3>
                <p class="text-zinc-500 font-medium text-sm">Fresh fruits and energizing treats for the whole family.
                </p>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-32 bg-primary">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-20 items-center">
            <div class="space-y-12 text-white">
                <h2 class="text-6xl md:text-8xl font-black leading-none uppercase tracking-tighter italic">EVERYBODY IS
                    A <br><span class="text-accent underline">WINNER</span></h2>
                <p class="text-xl font-medium opacity-80 leading-relaxed max-w-md">Our focus is on participation, not
                    competition. Every single child receives a commemorative gold medal at the finish line.</p>
                <div class="grid grid-cols-2 gap-8">
                    <div class="bg-white/10 p-6 rounded-3xl backdrop-blur-sm">
                        <p class="text-4xl font-black mb-1">08:00</p>
                        <p class="text-xs font-bold uppercase tracking-widest opacity-60">Morning Start</p>
                    </div>
                    <div class="bg-white/10 p-6 rounded-3xl backdrop-blur-sm">
                        <p class="text-4xl font-black mb-1">100%</p>
                        <p class="text-xs font-bold uppercase tracking-widest opacity-60">Safe Course</p>
                    </div>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1571008887538-b36bb32f4571?auto=format&fit=crop&q=80&w=800"
                    class="rounded-[3rem] shadow-2xl relative z-10" alt="Happy Medal">
                <div class="absolute -bottom-10 -right-10 size-64 bg-secondary rounded-[3rem] -z-0 rotate-12"></div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section id="register" class="py-40 px-6 text-center">
        <div class="max-w-4xl mx-auto space-y-12">
            <span class="material-symbols-outlined text-[10rem] text-primary/10">family_restroom</span>
            <h2 class="text-6xl md:text-[10rem] font-black leading-[0.85] tracking-tighter uppercase italic">LETS RUN
                <br><span class="text-secondary">TOGETHER!</span></h2>
            <div class="pt-8">
                <a href="/register"
                    class="inline-block bg-accent text-zinc-900 px-20 py-8 rounded-[3rem] font-black text-3xl comic shadow-xl shadow-amber-500/10 hover:scale-105 transition-all uppercase">
                    {{ $content['geral']['cta_text'] ?? 'REGISTER FAMILY' }}
                </a>
            </div>
            <p class="text-zinc-400 font-bold uppercase tracking-[0.4em] text-xs">Join thousands of families for the
                most joyful event of the year</p>
        </div>
    </section>

    <footer class="py-20 border-t border-zinc-100 bg-zinc-50">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-12">
            <div class="font-black text-3xl text-primary flex items-center gap-2 italic">
                <span class="material-symbols-outlined text-4xl">toys</span>
                FAMPLAY
            </div>
            <div class="flex gap-12 font-black text-[10px] uppercase tracking-widest text-zinc-400">
                <a href="#" class="hover:text-primary transition-colors">Course Map</a>
                <a href="#" class="hover:text-primary transition-colors">Volunteer</a>
                <a href="#" class="hover:text-primary transition-colors">FAQ</a>
            </div>
        </div>
        <div class="max-w-7xl mx-auto text-center mt-20 text-[10px] font-bold text-zinc-300 uppercase tracking-widest">
            © 2024 FAMILY PLAY CIRCUIT • JOY THROUGH MOVEMENT™
        </div>
    </footer>
</body>

</html>