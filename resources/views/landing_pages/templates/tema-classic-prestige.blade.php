<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Classic Prestige Marathon' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400;1,700&family=Montserrat:wght@300;400;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --accent:
                {{ $content['geral']['accent_color'] ?? '#b1976b' }}
            ;
            --navy: #0a1128;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fff;
            color: var(--navy);
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }

        .bg-navy {
            background-color: var(--navy);
        }

        .text-accent {
            color: var(--accent);
        }

        .border-accent {
            border-color: var(--accent);
        }

        .bg-accent {
            background-color: var(--accent);
        }

        .letter-spacing-huge {
            letter-spacing: 0.3em;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Prestige Header -->
    <header class="relative min-h-screen bg-navy overflow-hidden flex flex-col justify-center items-center text-white">
        <!-- Floating Elements -->
        <div class="absolute inset-0 z-0 opacity-20">
            <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1459313101143-efcd1a6b0d70' }}"
                class="w-full h-full object-cover" alt="Runners Silhouette">
        </div>

        <div class="relative z-10 text-center px-6 max-w-5xl">
            <div class="flex justify-center mb-12">
                <div class="size-20 border-2 border-accent rounded-full flex items-center justify-center p-4">
                    <span class="material-symbols-outlined text-4xl text-accent">military_tech</span>
                </div>
            </div>

            <p class="text-accent letter-spacing-huge text-[10px] font-bold mb-6 uppercase">
                {{ $content['geral']['edition'] ?? 'ESTABLISHED 1999 • ANNIVERSARY EDITION' }}
            </p>

            <h1 class="font-serif italic text-6xl md:text-9xl mb-12 leading-tight">
                {{ $content['geral']['title'] ?? 'PRESTIGE MARATHON' }}
            </h1>

            <div class="h-1 w-24 bg-accent mx-auto mb-12"></div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-12">
                <a href="#register"
                    class="bg-accent text-navy px-12 py-5 font-bold text-xs uppercase tracking-[0.2em] shadow-2xl hover:bg-white hover:text-navy transition-all duration-500">
                    {{ $content['geral']['cta_label'] ?? 'REQUEST INVITATION' }}
                </a>
                <a href="#about"
                    class="text-white border-b border-white/20 pb-1 text-xs font-bold uppercase tracking-widest hover:border-accent hover:text-accent transition-all">
                    The History
                </a>
            </div>
        </div>

        <!-- Bottom Nav Bar -->
        <div
            class="absolute bottom-0 w-full py-10 px-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center text-[10px] uppercase font-bold tracking-[0.3em] text-white/40">
            <div class="flex gap-12 mb-6 md:mb-0">
                <span>Boston</span>
                <span>London</span>
                <span>Berlin</span>
                <span>Tokyo</span>
            </div>
            <div>
                Official Prestige Partner 2024
            </div>
        </div>
    </header>

    <!-- Content Split -->
    <section id="about" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-24 items-center">
            <div class="space-y-12">
                <h2 class="font-serif text-5xl md:text-7xl italic leading-none text-navy">
                    {{ $content['geral']['intro_heading'] ?? 'A LEGACY OF EXCELLENCE' }}
                </h2>
                <p
                    class="text-zinc-500 text-lg font-light leading-relaxed first-letter:text-5xl first-letter:font-serif first-letter:float-left first-letter:mr-3 first-letter:text-accent">
                    {{ $content['geral']['intro_body'] ?? 'The Prestige Marathon represents the pinnacle of long-distance running. Since 1999, we have welcomed the worlds most dedicated athletes.' }}
                </p>
                <div class="grid grid-cols-2 gap-8 py-8 border-y border-zinc-100">
                    <div>
                        <p class="text-xs uppercase font-bold mb-2">Participant Cap</p>
                        <p class="text-3xl font-serif italic">2,500 Elite</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase font-bold mb-2">Total Purse</p>
                        <p class="text-3xl font-serif italic">$250,000</p>
                    </div>
                </div>
            </div>
            <div class="relative group">
                <div
                    class="absolute -inset-4 border border-zinc-100 -z-10 group-hover:-inset-2 transition-all duration-700">
                </div>
                <img src="https://images.unsplash.com/photo-1547483732-2ab8272d7c97?auto=format&fit=crop&q=80&w=800"
                    class="w-full grayscale hover:grayscale-0 transition-all duration-1000" alt="Marathon Finish">
                <div
                    class="absolute bottom-0 right-0 bg-navy text-white p-10 translate-x-12 translate-y-12 shadow-2xl hidden lg:block">
                    <p class="text-[10px] uppercase tracking-widest font-bold mb-4">Official Route</p>
                    <p class="font-serif text-2xl italic leading-tight">Historic Center <br>& Botanical Gardens</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="py-32 bg-zinc-50 border-y border-zinc-100">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-20">
            <div class="text-center group">
                <div class="size-1 px-4 bg-accent mx-auto mb-10 group-hover:w-16 transition-all duration-500"></div>
                <h4 class="font-serif text-3xl mb-4 italic">
                    {{ $content['geral']['service_1'] ?? 'Elite Athlete Support' }}</h4>
                <p class="text-zinc-400 text-xs font-medium uppercase tracking-widest px-10">Advanced medical and
                    coaching integration.</p>
            </div>
            <div class="text-center group">
                <div class="size-1 px-4 bg-accent mx-auto mb-10 group-hover:w-16 transition-all duration-500"></div>
                <h4 class="font-serif text-3xl mb-4 italic">{{ $content['geral']['service_2'] ?? 'VIP Lounge Access' }}
                </h4>
                <p class="text-zinc-400 text-xs font-medium uppercase tracking-widest px-10">Exclusive recovery zones
                    and private catering.</p>
            </div>
            <div class="text-center group">
                <div class="size-1 px-4 bg-accent mx-auto mb-10 group-hover:w-16 transition-all duration-500"></div>
                <h4 class="font-serif text-3xl mb-4 italic">
                    {{ $content['geral']['service_3'] ?? 'Museum Edition Medal' }}</h4>
                <p class="text-zinc-400 text-xs font-medium uppercase tracking-widest px-10">24k gold-plated limited
                    edition collectors piece.</p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section id="register" class="py-48 px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <span class="material-symbols-outlined text-6xl text-accent mb-12">auto_awesome</span>
            <h2 class="font-serif text-5xl md:text-8xl italic mb-12 text-navy leading-none">Are you ready to join
                <br>the hall of legends?</h2>
            <div class="h-24 w-px bg-zinc-100 mx-auto mb-16"></div>
            <a href="/invitation"
                class="bg-navy text-white px-20 py-7 font-bold text-xs uppercase tracking-[0.3em] hover:bg-accent hover:text-navy transition-all duration-700">
                {{ $content['geral']['cta_label'] ?? 'REQUEST INVITATION' }}
            </a>
            <p class="mt-12 text-zinc-400 text-[10px] font-bold uppercase tracking-widest">Selected applications will be
                notified within 7 business days.</p>
        </div>
    </section>

    <footer class="py-20 border-t border-zinc-100 text-center">
        <div class="size-12 border border-accent rounded-full flex items-center justify-center mx-auto mb-10">
            <span class="material-symbols-outlined text-xl text-accent">workspace_premium</span>
        </div>
        <p class="text-navy font-bold uppercase tracking-[0.2em] text-[10px] mb-8">Official Global Circuits • Marathon
            Prestige</p>
        <p class="text-zinc-300 text-[10px] uppercase font-bold tracking-[0.4em]">&copy; 2024 THE PRESTIGE SERIES. ALL
            RIGHTS RESERVED.</p>
    </footer>
</body>

</html>