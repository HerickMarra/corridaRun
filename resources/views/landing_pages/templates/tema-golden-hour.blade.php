<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Golden Hour Run' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Montserrat:wght@200;400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --primary:
                {{ $content['geral']['primary_color'] ?? '#f59e0b' }}
            ;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #0f1012;
            color: #fff;
        }

        .font-marcellus {
            font-family: 'Marcellus', serif;
        }

        .text-sunset {
            color: var(--primary);
        }

        .bg-sunset {
            background-color: var(--primary);
        }

        .sunset-gradient {
            background: linear-gradient(to bottom, rgba(15, 16, 18, 0) 0%, rgba(15, 16, 18, 1) 100%);
        }

        .warm-overlay {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(239, 68, 68, 0.2));
            mix-blend-mode: overlay;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Hero Section -->
    <header class="relative min-h-screen flex items-end p-6 md:p-12 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1550021671-b0db3dc0266e' }}"
                class="w-full h-full object-cover" alt="Run at Sunset">
            <div class="absolute inset-0 warm-overlay"></div>
            <div class="absolute inset-0 sunset-gradient"></div>
        </div>

        <div
            class="relative z-10 w-full max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-12 pb-12">
            <div class="space-y-6">
                <span class="text-sunset font-marcellus text-xl tracking-[0.3em] uppercase block">
                    {{ $content['geral']['tagline'] ?? 'CHASE THE LAST LIGHT' }}
                </span>
                <h1 class="font-marcellus text-7xl md:text-[10rem] leading-[0.8] tracking-tighter">
                    {{ $content['geral']['title'] ?? 'GOLDEN HOUR' }}
                </h1>
            </div>
            <div class="md:text-right space-y-8">
                <p class="text-xl font-light text-white/60 max-w-sm ml-auto">
                    {{ $content['geral']['intro_text'] ?? 'There is a magic window when the day meets the night. Run through scenic viewpoints.' }}
                </p>
                <a href="#register"
                    class="inline-block bg-white text-black px-12 py-5 rounded-full font-bold text-sm tracking-widest hover:bg-sunset hover:text-white transition-all duration-500 uppercase">
                    {{ $content['geral']['cta_text'] ?? 'SAVE YOUR SUNSET' }}
                </a>
            </div>
        </div>
    </header>

    <!-- Scenic Cards -->
    <section class="py-40 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-32 items-center">
            <div class="relative group">
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&q=80&w=400"
                        class="rounded-[2rem] h-64 w-full object-cover" alt="Sunset 1">
                    <img src="https://images.unsplash.com/photo-1533167649158-6d508895b680?auto=format&fit=crop&q=80&w=400"
                        class="rounded-[2rem] h-64 w-full object-cover mt-12" alt="Sunset 2">
                </div>
            </div>
            <div class="space-y-12">
                <h2 class="font-marcellus text-5xl md:text-7xl">Elegance in <br>every stride.</h2>
                <div class="space-y-8">
                    <div class="flex items-start gap-8 group">
                        <span
                            class="font-marcellus text-3xl text-sunset group-hover:scale-110 transition-transform">01</span>
                        <div>
                            <h4 class="font-marcellus text-xl mb-2">
                                {{ $content['geral']['feature_1'] ?? 'Oceanfront Route' }}</h4>
                            <p class="text-white/40 text-sm">Experience the rhythmic sound of waves as you run along the
                                coastal path at peak beauty.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-8 group">
                        <span
                            class="font-marcellus text-3xl text-sunset group-hover:scale-110 transition-transform">02</span>
                        <div>
                            <h4 class="font-marcellus text-xl mb-2">
                                {{ $content['geral']['feature_2'] ?? 'Sunset Medals' }}</h4>
                            <p class="text-white/40 text-sm">Finisher medals crafted with a dual-tone finish that
                                captures the evening glow.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-8 group">
                        <span
                            class="font-marcellus text-3xl text-sunset group-hover:scale-110 transition-transform">03</span>
                        <div>
                            <h4 class="font-marcellus text-xl mb-2">
                                {{ $content['geral']['feature_3'] ?? 'Post-Run Sunset Party' }}</h4>
                            <p class="text-white/40 text-sm">Celebrate your achievement with chill-out music and
                                refreshing sunset cocktails.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Full Image Quote -->
    <section class="relative h-[100vh] flex items-center justify-center text-center px-6">
        <img src="https://images.unsplash.com/photo-1528605248644-14dd04cb21c7?auto=format&fit=crop&q=80&w=1920"
            class="absolute inset-0 w-full h-full object-cover" alt="Runner Silhouette">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 max-w-4xl space-y-12">
            <span class="material-symbols-outlined text-sunset text-6xl">flare</span>
            <h3 class="font-marcellus text-5xl md:text-8xl leading-none italic">"The magic is real, you just have to run
                for it."</h3>
            <div class="flex justify-center items-center gap-6">
                <div class="h-px w-12 bg-sunset"></div>
                <p class="text-[10px] font-bold uppercase tracking-[0.5em] text-white/60">Official Golden Hour Circuit
                </p>
                <div class="h-px w-12 bg-sunset"></div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section id="register" class="py-48 px-6 text-center">
        <div class="max-w-3xl mx-auto space-y-12">
            <h2 class="font-marcellus text-5xl md:text-9xl leading-tight">Don't let the <br>light fade.</h2>
            <div class="p-8 border-y border-white/5 inline-block">
                <a href="/register"
                    class="bg-sunset text-white px-16 py-6 rounded-full font-bold text-sm tracking-widest hover:bg-white hover:text-black transition-all duration-700 uppercase ring-8 ring-sunset/10">
                    {{ $content['geral']['cta_text'] ?? 'SAVE YOUR SUNSET' }}
                </a>
            </div>
            <p class="text-white/30 text-[10px] uppercase font-bold tracking-[0.4em]">Registrations open while positions
                last • 2024 Series</p>
        </div>
    </section>

    <footer class="py-24 bg-[#0a0b0d] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-12">
            <div class="font-marcellus text-2xl tracking-widest text-sunset">GOLDENHOUR</div>
            <div class="flex gap-12 font-bold text-[10px] uppercase tracking-widest text-white/30">
                <a href="#" class="hover:text-sunset transition-colors">Routes</a>
                <a href="#" class="hover:text-sunset transition-colors">Safety</a>
                <a href="#" class="hover:text-sunset transition-colors">Results</a>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-20 text-center">
            <p class="text-[10px] font-bold text-white/10 uppercase tracking-[0.5em]">© 2024 GOLDEN HOUR RUNNING • THE
                ART OF THE LAST MILE</p>
        </div>
    </footer>
</body>

</html>