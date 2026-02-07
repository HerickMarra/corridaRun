<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Social Circle Run' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Quicksand:wght@300;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --primary:
                {{ $content['geral']['primary_color'] ?? '#ff6b6b' }}
            ;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #fff9f9;
            color: #4a4a4a;
        }

        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
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

        .soft-shadow {
            box-shadow: 0 20px 40px -10px rgba(255, 107, 107, 0.1);
        }

        .blob {
            border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Nav -->
    <nav class="p-8 flex justify-between items-center max-w-7xl mx-auto">
        <div class="font-fredoka font-bold text-2xl text-primary tracking-tight flex items-center gap-2">
            <span class="material-symbols-outlined text-3xl">group</span>
            SOCIALCIRCLE
        </div>
        <div class="hidden md:flex gap-8 font-bold text-sm text-zinc-400">
            <a href="#" class="hover:text-primary transition-colors">Our Story</a>
            <a href="#" class="hover:text-primary transition-colors">Meetups</a>
            <a href="#" class="hover:text-primary transition-colors">Shop</a>
        </div>
    </nav>

    <!-- Hero -->
    <header class="py-20 px-6">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-20">
            <div class="lg:w-1/2 space-y-10">
                <span
                    class="bg-primary/10 text-primary font-fredoka font-bold px-6 py-2 rounded-full uppercase tracking-widest text-xs">
                    {{ $content['geral']['tagline'] ?? 'GOOD VIBES ONLY' }}
                </span>
                <h1 class="font-fredoka font-bold text-6xl md:text-8xl leading-[0.9] text-zinc-800">
                    {{ $content['geral']['title'] ?? 'RUN WITH FRIENDS' }}
                </h1>
                <p class="text-xl text-zinc-500 leading-relaxed font-medium">
                    {{ $content['geral']['intro_text'] ?? 'Running is better when shared. Join our community for casual morning runs, coffee talks, and a supportive atmosphere.' }}
                </p>
                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="#register"
                        class="bg-primary text-white px-12 py-5 rounded-2xl font-bold text-lg soft-shadow hover:-translate-y-1 transition-all">
                        {{ $content['geral']['cta_text'] ?? 'JOIN THE CIRCLE' }}
                    </a>
                    <a href="#"
                        class="flex items-center gap-4 bg-white px-8 py-5 rounded-2xl font-bold border border-zinc-100 hover:bg-zinc-50 transition-all">
                        <span class="material-symbols-outlined text-primary">play_circle</span>
                        See How We Run
                    </a>
                </div>
                <div class="flex items-center gap-4 pt-8">
                    <div class="flex -space-x-4">
                        <img src="https://i.pravatar.cc/150?u=1" class="size-12 rounded-full border-4 border-white"
                            alt="Member">
                        <img src="https://i.pravatar.cc/150?u=2" class="size-12 rounded-full border-4 border-white"
                            alt="Member">
                        <img src="https://i.pravatar.cc/150?u=3" class="size-12 rounded-full border-4 border-white"
                            alt="Member">
                    </div>
                    <p class="text-sm font-bold text-zinc-400">+1,200 local members</p>
                </div>
            </div>
            <div class="lg:w-1/2 relative">
                <div class="absolute inset-0 bg-primary/10 blob -z-10 scale-110 rotate-12"></div>
                <div class="rounded-[3rem] overflow-hidden rotate-3 soft-shadow border-[12px] border-white">
                    <img src="{{ $content['geral']['hero_image'] ?? 'https://images.unsplash.com/photo-1549438343-98282367ea1b' }}"
                        class="w-full aspect-[4/5] object-cover" alt="Happy Runners">
                </div>
                <div
                    class="absolute -bottom-10 -left-10 bg-white p-8 rounded-3xl soft-shadow max-w-[240px] border border-zinc-50">
                    <p class="text-[10px] font-black uppercase text-primary tracking-widest mb-2">Next Meetup</p>
                    <p class="font-fredoka text-xl text-zinc-800">Ipanema Beach Walk & Run</p>
                    <p class="text-sm font-bold text-zinc-400 mt-2">7:00 AM • July 14</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Values -->
    <section class="py-40 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-3 gap-16">
                <div class="space-y-6">
                    <div class="size-16 rounded-3xl bg-blue-50 flex items-center justify-center text-blue-500">
                        <span class="material-symbols-outlined text-3xl">coffee</span>
                    </div>
                    <h3 class="font-fredoka text-3xl text-zinc-800">
                        {{ $content['geral']['feat_1_title'] ?? 'Coffee & Run' }}</h3>
                    <p class="text-zinc-500 font-medium">Post-run brews are essential. We explore the best local
                        roasters every week.</p>
                </div>
                <div class="space-y-6 pt-12">
                    <div class="size-16 rounded-3xl bg-pink-50 flex items-center justify-center text-pink-500">
                        <span class="material-symbols-outlined text-3xl">favorite</span>
                    </div>
                    <h3 class="font-fredoka text-3xl text-zinc-800">
                        {{ $content['geral']['feat_2_title'] ?? 'No Pressure Pace' }}</h3>
                    <p class="text-zinc-500 font-medium">Beginner? Veteran? Doesn't matter. We have groups for every
                        level of fitness.</p>
                </div>
                <div class="space-y-6">
                    <div class="size-16 rounded-3xl bg-amber-50 flex items-center justify-center text-amber-500">
                        <span class="material-symbols-outlined text-3xl">diversity_2</span>
                    </div>
                    <h3 class="font-fredoka text-3xl text-zinc-800">
                        {{ $content['geral']['feat_3_title'] ?? 'Community Events' }}</h3>
                    <p class="text-zinc-500 font-medium">Picnics, workshops, and charity runs that make a real
                        difference in our city.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Banner -->
    <section class="py-20 overflow-hidden">
        <div class="flex gap-8 animate-[scroll_40s_linear_infinite] whitespace-nowrap">
            <img src="https://images.unsplash.com/photo-1550021671-b0db3dc0266e?auto=format&fit=crop&q=80&w=400"
                class="h-64 rounded-3xl" alt="Community 1">
            <img src="https://images.unsplash.com/photo-1452626038306-9aae5e071dd3?auto=format&fit=crop&q=80&w=400"
                class="h-64 rounded-3xl" alt="Community 2">
            <img src="https://images.unsplash.com/photo-1547483732-2ab8272d7c97?auto=format&fit=crop&q=80&w=400"
                class="h-64 rounded-3xl" alt="Community 3">
            <img src="https://images.unsplash.com/photo-1517404215738-15263e9f9178?auto=format&fit=crop&q=80&w=400"
                class="h-64 rounded-3xl" alt="Community 4">
            <img src="https://images.unsplash.com/photo-1530541930197-ff16ac917b0e?auto=format&fit=crop&q=80&w=400"
                class="h-64 rounded-3xl" alt="Community 5">
            <!-- Duplicate for loop -->
            <img src="https://images.unsplash.com/photo-1550021671-b0db3dc0266e?auto=format&fit=crop&q=80&w=400"
                class="h-64 rounded-3xl" alt="Community 1">
            <img src="https://images.unsplash.com/photo-1452626038306-9aae5e071dd3?auto=format&fit=crop&q=80&w=400"
                class="h-64 rounded-3xl" alt="Community 2">
        </div>
    </section>

    <!-- CTA -->
    <section id="register" class="py-40 px-6">
        <div class="max-w-4xl mx-auto text-center space-y-12">
            <h2 class="font-fredoka text-6xl md:text-8xl text-zinc-800">The circle is <br>open for you.</h2>
            <p class="text-zinc-500 font-bold uppercase tracking-widest text-xs">NO MEMBERSHIP FEES • JUST LACE UP AND
                SHOW UP</p>
            <a href="/register"
                class="inline-block bg-primary text-white px-20 py-7 rounded-[2rem] font-fredoka text-2xl soft-shadow hover:scale-105 transition-all">
                {{ $content['geral']['cta_text'] ?? 'JOIN THE CIRCLE' }}
            </a>
        </div>
    </section>

    <footer class="py-20 border-t border-zinc-100">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-12">
            <div class="font-fredoka font-bold text-xl text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">group</span>
                SOCIALCIRCLE
            </div>
            <div class="flex gap-8 font-bold text-xs uppercase tracking-widest text-zinc-400">
                <a href="#" class="hover:text-primary transition-colors">Instagram</a>
                <a href="#" class="hover:text-primary transition-colors">TikTok</a>
                <a href="#" class="hover:text-primary transition-colors">Strava</a>
            </div>
            <p class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest">© 2024 SOCIAL RUN COMMUNITY • MADE
                WITH LOVE</p>
        </div>
    </footer>

    <style>
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }
    </style>
</body>

</html>