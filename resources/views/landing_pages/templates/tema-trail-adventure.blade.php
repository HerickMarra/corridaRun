<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Trail Adventure' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&family=Playfair+Display:ital,wght@1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --primary:
                {{ $content['geral']['primary_color'] ?? '#8b5e3c' }}
            ;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #fdfcf9;
            color: #2a2118;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .text-primary {
            color: var(--primary);
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .border-primary {
            border-color: var(--primary);
        }
    </style>
</head>

<body class="antialiased">
    <!-- Hero -->
    <header class="relative min-h-[90vh] flex items-center justify-center p-6">
        <div class="absolute inset-4 rounded-[2.5rem] overflow-hidden">
            <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1551632811-561732d1e306' }}"
                class="w-full h-full object-cover" alt="Trail Background">
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <div class="relative z-10 text-center max-w-4xl">
            <p class="text-white/80 uppercase tracking-[0.3em] text-xs font-bold mb-6">
                {{ $content['geral']['subtitle'] ?? 'WILD TRAIL SERIES' }}
            </p>
            <h1 class="font-playfair text-white text-6xl md:text-9xl leading-[0.8] mb-10">
                {{ $content['geral']['title'] ?? 'CONQUER THE PEAKS' }}
            </h1>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#register"
                    class="bg-primary text-white border-2 border-primary px-10 py-4 rounded-full font-bold text-sm tracking-widest hover:bg-transparent hover:text-white transition-all uppercase">
                    {{ $content['geral']['cta_text'] ?? 'START YOUR ASCENT' }}
                </a>
                <a href="#about"
                    class="bg-white/10 backdrop-blur-md text-white border-2 border-white/20 px-10 py-4 rounded-full font-bold text-sm tracking-widest hover:bg-white/20 transition-all uppercase">
                    Learn More
                </a>
            </div>
        </div>
    </header>

    <!-- Stats -->
    <section class="relative z-20 -mt-20 px-6">
        <div
            class="max-w-5xl mx-auto bg-white rounded-3xl p-12 shadow-2xl shadow-[#8b5e3c]/10 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <div class="border-b md:border-b-0 md:border-r border-zinc-100 pb-8 md:pb-0">
                <h4 class="text-4xl font-black text-primary mb-2">{{ $content['geral']['stat_1_val'] ?? '2.4k' }}</h4>
                <p class="text-[10px] uppercase font-bold tracking-widest text-zinc-400">
                    {{ $content['geral']['stat_1_label'] ?? 'Elevation Gain' }}</p>
            </div>
            <div class="border-b md:border-b-0 md:border-r border-zinc-100 pb-8 md:pb-0">
                <h4 class="text-4xl font-black text-primary mb-2">{{ $content['geral']['stat_2_val'] ?? '42km' }}</h4>
                <p class="text-[10px] uppercase font-bold tracking-widest text-zinc-400">
                    {{ $content['geral']['stat_2_label'] ?? 'Pure Nature' }}</p>
            </div>
            <div>
                <h4 class="text-4xl font-black text-primary mb-2">{{ $content['geral']['stat_3_val'] ?? '100%' }}</h4>
                <p class="text-[10px] uppercase font-bold tracking-widest text-zinc-400">
                    {{ $content['geral']['stat_3_label'] ?? 'Adrenaline' }}</p>
            </div>
        </div>
    </section>

    <!-- Intro -->
    <section id="about" class="py-32 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-20 items-center">
            <div class="md:w-1/2 space-y-8">
                <span
                    class="inline-block py-1.5 px-4 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-lg">Heritage</span>
                <h2 class="font-playfair text-5xl md:text-7xl leading-tight">
                    {{ $content['geral']['intro_title'] ?? 'WHERE NATURE MEETS CHALLENGE' }}</h2>
                <p class="text-zinc-600 text-lg leading-relaxed">
                    {{ $content['geral']['intro_text'] ?? 'Experience the raw beauty of the mountains. Our trails take you through dense forests and rocky ridges.' }}
                </p>
                <div class="flex gap-6 pt-6">
                    <div class="flex items-center gap-3">
                        <div class="size-12 rounded-2xl bg-zinc-100 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">terrain</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-none">Rocky Paths</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="size-12 rounded-2xl bg-zinc-100 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">forest</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-none">Pure Green</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:w-1/2 grid grid-cols-2 gap-4">
                <div class="space-y-4 pt-12">
                    <img src="https://images.unsplash.com/photo-1541625602330-2277a1cd1f59?auto=format&fit=crop&q=80&w=400"
                        class="rounded-3xl shadow-xl" alt="Trail Detail">
                    <img src="https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&q=80&w=400"
                        class="rounded-3xl shadow-xl" alt="Summit View">
                </div>
                <div class="space-y-4">
                    <img src="https://images.unsplash.com/photo-1516567841141-ad37d8848836?auto=format&fit=crop&q=80&w=400"
                        class="rounded-3xl shadow-xl" alt="Forest Trail">
                    <img src="https://images.unsplash.com/photo-1533167649158-6d508895b680?auto=format&fit=crop&q=80&w=400"
                        class="rounded-3xl shadow-xl" alt="Runner">
                </div>
            </div>
        </div>
    </section>

    <!-- Register CTA -->
    <section id="register" class="py-24 px-6">
        <div
            class="max-w-7xl mx-auto rounded-[3rem] bg-[#2a2118] p-12 md:p-24 text-center text-white relative overflow-hidden">
            <div
                class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/dark-leather.png')]">
            </div>
            <div class="relative z-10">
                <h2 class="font-playfair text-4xl md:text-7xl mb-8">Ready to answer the call?</h2>
                <p class="text-white/40 mb-12 uppercase tracking-[0.4em] text-[10px] font-bold">Registration closing
                    soon for the autumn season</p>
                <a href="/register"
                    class="bg-primary text-white px-12 py-5 rounded-full font-bold text-sm tracking-widest hover:scale-105 transition-all uppercase ring-8 ring-primary/20">
                    {{ $content['geral']['cta_text'] ?? 'START YOUR ASCENT' }}
                </a>
            </div>
        </div>
    </section>

    <footer class="py-24 border-t border-zinc-100 text-center">
        <h4 class="font-playfair text-2xl mb-8 italic">TrailAdventure</h4>
        <div class="flex justify-center gap-12 mb-12 text-[10px] font-black uppercase tracking-widest text-zinc-400">
            <a href="#" class="hover:text-primary transition-colors">Safety Guide</a>
            <a href="#" class="hover:text-primary transition-colors">Course Map</a>
            <a href="#" class="hover:text-primary transition-colors">Equipment</a>
        </div>
        <p class="text-zinc-300 text-[10px] uppercase font-bold tracking-[0.3em]">&copy; 2024 WILD TRAIL SERIES CO.</p>
    </footer>
</body>

</html>