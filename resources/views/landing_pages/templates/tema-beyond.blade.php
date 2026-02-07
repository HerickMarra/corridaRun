<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Brasília Run Community | Beyond Miles</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&amp;family=Inter:wght@400;500;700;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#f97316", // Vibrant Orange
                        "background-light": "#f8fafc",
                        "background-dark": "#0a0a0a",
                    },
                    fontFamily: {
                        display: ["'Archivo Black'", "sans-serif"],
                        sans: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "4px",
                    },
                },
            },
        };
    </script>
    <style>
        .italic-bold {
            font-style: italic;
            font-weight: 900;
            text-transform: uppercase;
        }

        .hero-gradient {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.8));
        }

        .feature-card:hover .arrow-move {
            transform: translateX(4px);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white transition-colors duration-300">
    <nav class="fixed top-0 w-full z-50 bg-white/10 dark:bg-black/20 backdrop-blur-md border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="font-display italic-bold text-2xl tracking-tighter">BRASÍLIA<span
                        class="text-primary">RUN</span></span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-widest text-white/80">
                <a class="hover:text-primary transition-colors" href="#">Home</a>
                <a class="hover:text-primary transition-colors" href="#">Gallery</a>
                <a class="hover:text-primary transition-colors" href="#">Event</a>
                <a class="hover:text-primary transition-colors" href="#">About</a>
            </div>
            <a class="bg-white text-black text-xs font-bold px-6 py-2.5 rounded-full hover:bg-primary hover:text-white transition-all uppercase tracking-tight"
                href="#">Join a Member</a>
        </div>
    </nav>
    <header class="relative h-screen flex items-center justify-center overflow-hidden">
        <img alt="Runners on a track at dusk" class="absolute inset-0 w-full h-full object-cover"
            src="{{ asset($content['geral']['image_1'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgpFsliDXK869mGO4es3dVf4gdPfGYETQef7G7_qlxREN-KqY1fAevPc_K6YyjMfkFkXT9QRPmMKKlzakOn6lvQHxUkyZZkPdLtwG0oLZknFDFcKahOGYJTLWcPATl5e7lIrIjgt2hd3xnou5cqwWrNc2t7DoO2_5nYHTmoG9-8HKIu1xf88sA7EoBgbIbcVsMn11G3jxJr-q2bUeqJOLVjshzaA_-UuzekHAlSo_fsewVv4fMlcgI171Dp1J8b6xap8VhA4bb--o') }}" />
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="relative z-10 text-center px-6">
            <h1 {{ $attributes ?? '' }} class="font-display italic-bold text-5xl md:text-8xl leading-none mb-4 tracking-tighter text-white">{{ $content['geral']['title'] ?? 'BEYOND MILESWE BUILD BONDS' }}</h1>
            <p {{ $attributes ?? '' }} class="text-white/60 text-xs md:text-sm tracking-[0.2em] uppercase max-w-lg mx-auto">{{ $content['geral']['description_1'] ?? 'Brasília is a running community that connects passionate runners.' }}</p>
        </div>
    </header>
    <section class="bg-[#111] dark:bg-zinc-900/50 py-12 border-y border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <p {{ $attributes ?? '' }} class="text-[10px] uppercase tracking-[0.3em] text-center text-zinc-500 mb-8 font-bold">{{ $content['geral']['description_2'] ?? 'Support By' }}</p>
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-40 grayscale contrast-125">
                <span class="font-display text-xl italic text-white">COROS</span>
                <span class="font-display text-xl italic text-white">asics</span>
                <span class="font-display text-xl italic text-white uppercase tracking-tighter">Strava</span>
                <span class="font-display text-xl italic text-white">saucony</span>
                <span class="font-display text-xl italic text-white">GARMIN</span>
            </div>
        </div>
    </section>
    <section class="bg-white dark:bg-background-dark py-24">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <p {{ $attributes ?? '' }} class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary">{{ $content['geral']['description_3'] ?? 'What is Brasília Run?' }}</p>
                <h2 {{ $attributes ?? '' }} class="font-display italic-bold text-5xl md:text-7xl leading-tight text-slate-900 dark:text-white">{{ $content['geral']['heading_2'] ?? 'MORE THAN JUST RUNNING' }}</h2>
                <p {{ $attributes ?? '' }} class="text-slate-600 dark:text-zinc-400 max-w-md leading-relaxed">{{ $content['geral']['description_4'] ?? 'Born from a passion for movement and innovation, Brasília Run is about friendship, motivation, and
                    growth. We believe every stride brings us closer together.' }}</p>
            </div>
            <div class="relative rounded-2xl overflow-hidden aspect-[4/3] group">
                <img alt="Action shot of a runner"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                    src="{{ asset($content['geral']['image_2'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuBenMUVFD5jCWCM1boAts_JrYV11nq80fOTPIt_1WAH1cjHpMDgn9OXiDpNGWXxwmp9jTyae7M1QmryBaVV9nF42J7lyU_Htbr-wCwE_SkBJOzd7S9df4DuqVVJ3xffpaePRbvMvxDfdqQltXLbWXxSnH3_qp49pP95Wg2PNT6oKHlAyLRhLqVdDXWrv3NHHRerY74_mTtwdqbei5ewqGFzEtlHKxYGvR7YfMghh1D0cJUE4kKsCrTOv7Umz2BrqZjvY4o-ikyQocM') }}" />
            </div>
        </div>
    </section>
    <section class="bg-slate-50 dark:bg-zinc-950 py-24 border-y border-zinc-200 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-4">Features</p>
                    <h2 {{ $attributes ?? '' }}
                        class="font-display italic-bold text-5xl md:text-7xl leading-tight text-slate-900 dark:text-white uppercase">{{ $content['geral']['heading_3'] ?? 'RUN TOGETHER FEEL THE ENERGY' }}</h2>
                </div>
                <p {{ $attributes ?? '' }} class="text-slate-500 dark:text-zinc-400 max-w-xs text-sm leading-relaxed md:pb-4">{{ $content['geral']['description_5'] ?? 'Join weekly runs in your city and experience the joy of running with people who share your passion.' }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="group feature-card flex flex-col bg-white dark:bg-zinc-900 overflow-hidden border border-zinc-200 dark:border-white/5">
                    <div class="p-8 pb-4">
                        <span class="material-symbols-outlined text-primary mb-4 text-3xl">fitness_center</span>
                        <h3 {{ $attributes ?? '' }} class="font-display italic text-lg mb-2 text-slate-900 dark:text-white uppercase">{{ $content['geral']['heading_4'] ?? 'Training
                            Plans' }}</h3>
                        <p {{ $attributes ?? '' }} class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed mb-8">{{ $content['geral']['description_6'] ?? 'Personalized tips for
                            all levels, from beginners to elites.' }}</p>
                    </div>
                    <div class="relative h-64 mt-auto">
                        <img alt="Training"
                            class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity"
                            src="{{ asset($content['geral']['image_3'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuBnpBq9Z54i_hO5qbr9KkFmsAYth2Kr792faM4i-VYjd68iYeop_BrPivoSHz_UqkI6X7ZJrc8VPq_uTN9LdQvAeWQNXUXDaMdsRe3OrImT13p-V0gnVhamMT5TexpnApkdyqKAd51HAUgJGDUaqd8PPalNrshrC3ioc64VXu0-hU--t3rY-ViOO52mU5lTmMSXUHtanGNf6qQNTYTDOlHXYr1-1GQEarQbw9DKgvKXf5YDJ4Wrdedosn59F8N0B-lKdR7x_6MqDUU') }}" />
                        <div class="absolute inset-0 bg-black/40 flex items-end p-6">
                            <a class="flex items-center gap-2 text-[10px] font-bold uppercase text-white tracking-widest"
                                href="#">Read More <span
                                    class="material-symbols-outlined text-sm arrow-move transition-transform">arrow_forward</span></a>
                        </div>
                    </div>
                </div>
                <div
                    class="group feature-card flex flex-col bg-white dark:bg-zinc-900 overflow-hidden border border-zinc-200 dark:border-white/5">
                    <div class="p-8 pb-4">
                        <span class="material-symbols-outlined text-primary mb-4 text-3xl">groups</span>
                        <h3 {{ $attributes ?? '' }} class="font-display italic text-lg mb-2 text-slate-900 dark:text-white uppercase">{{ $content['geral']['heading_5'] ?? 'Group Runs' }}</h3>
                        <p {{ $attributes ?? '' }} class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed mb-8">{{ $content['geral']['description_7'] ?? 'Weekly community runs
                            in different neighborhoods of the capital.' }}</p>
                    </div>
                    <div class="relative h-64 mt-auto">
                        <img alt="Group Runs"
                            class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity"
                            src="{{ asset($content['geral']['image_4'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuAcPnBid4ndO8c7EgAvDh_UPkVVJ6fiuT77IV7Dd0KuRC-0vrL9anMRX5G_vSDXrj-JuWTHbyH6xPg3eQk6vKBq_vRSVqn7coPtO-YwwvwlPkIyGF7BWkZbl2PghqtFWONRkkcqf9n_s4c_xHj3nlJBFqbqPvkLJnoGMrLZtMiGGcHj31BiEOravZpGLUG7cr978PBywAgC1X-Uq1GpeYuroPCi7ulD8C6RU0u1Y6vtFzin_hZFxD6PhPt8guoUMuHMEBtsFE9gUns') }}" />
                        <div class="absolute inset-0 bg-black/40 flex items-end p-6">
                            <a class="flex items-center gap-2 text-[10px] font-bold uppercase text-white tracking-widest"
                                href="#">Read More <span
                                    class="material-symbols-outlined text-sm arrow-move transition-transform">arrow_forward</span></a>
                        </div>
                    </div>
                </div>
                <div
                    class="group feature-card flex flex-col bg-white dark:bg-zinc-900 overflow-hidden border border-zinc-200 dark:border-white/5">
                    <div class="p-8 pb-4">
                        <span class="material-symbols-outlined text-primary mb-4 text-3xl">diversity_3</span>
                        <h3 {{ $attributes ?? '' }} class="font-display italic text-lg mb-2 text-slate-900 dark:text-white uppercase">{{ $content['geral']['heading_6'] ?? 'Community' }}</h3>
                        <p {{ $attributes ?? '' }} class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed mb-8">{{ $content['geral']['description_8'] ?? 'Connect with fellow
                            runners and share your daily progress.' }}</p>
                    </div>
                    <div class="relative h-64 mt-auto">
                        <img alt="Community"
                            class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity"
                            src="{{ asset($content['geral']['image_5'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCeD_jUMvwLx6dvXlaWEyleYRh5VJD-A0sACD0FWGgmno3gu-qQujAfo8Pt7aoAZ-bwAifTxRDuuIXH1mcLsunmtn_xmx1p9WMojVmk6VAegS0JM1YWA9fObJbo1N256outHosKEztzi1KYaDbAsku3EjkjS5pUh34Hel0orINjYFXkrLsjl2rifxdjkaWEvelGuLjYA9SbR7pJrNtKjStAkb3rZLsPwiRrkilnShn9t3MnhMqUwhY1xa6CtXRVQvBDBWCCZRGhkLE') }}" />
                        <div class="absolute inset-0 bg-black/40 flex items-end p-6">
                            <a class="flex items-center gap-2 text-[10px] font-bold uppercase text-white tracking-widest"
                                href="#">Read More <span
                                    class="material-symbols-outlined text-sm arrow-move transition-transform">arrow_forward</span></a>
                        </div>
                    </div>
                </div>
                <div
                    class="group feature-card flex flex-col bg-white dark:bg-zinc-900 overflow-hidden border border-zinc-200 dark:border-white/5">
                    <div class="p-8 pb-4">
                        <span class="material-symbols-outlined text-primary mb-4 text-3xl">calendar_month</span>
                        <h3 {{ $attributes ?? '' }} class="font-display italic text-lg mb-2 text-slate-900 dark:text-white uppercase">{{ $content['geral']['heading_7'] ?? 'Events' }}</h3>
                        <p {{ $attributes ?? '' }} class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed mb-8">{{ $content['geral']['description_9'] ?? 'Monthly events to keep
                            you motivated and challenge your limits.' }}</p>
                    </div>
                    <div class="relative h-64 mt-auto">
                        <img alt="Events"
                            class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity"
                            src="{{ asset($content['geral']['image_6'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDVOkFbdqegghoYSzaBMV2h1JZ8X0YUiiDV9dM6rL1m-jJXUCNqzEwKTmON86XoDsnwqhsvakSGYJTh4vznEpPxJL-XyRBPiwgDVjiP2wDHWT02ca2cb1VVpGegCvnHyS2-mHTN4m_eCW_KoZcovo6ZKNUF_57anfcTcoLgq2rZj-KVByY2zFXLY5Iv2g3UzxBvlQTbd8f_5_DvPvcQatwg-JBkN0fMKB3ARSSzW_blfpH7tw_MOLz065tgd2SZLrVYjUhK0wiyA4w') }}" />
                        <div class="absolute inset-0 bg-black/40 flex items-end p-6">
                            <a class="flex items-center gap-2 text-[10px] font-bold uppercase text-white tracking-widest"
                                href="#">Read More <span
                                    class="material-symbols-outlined text-sm arrow-move transition-transform">arrow_forward</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="relative bg-zinc-900 py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-40">
            <img alt="Brasilia Landscape" class="w-full h-full object-cover grayscale"
                src="{{ asset($content['geral']['image_7'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCXSXkZWICZtuMTKxWpIqpJzruhYMtV0wt9vqvzAh0-RUpO7SpXR4qqcAlycqwYpV1dz9tJ3c7Ed1KRRd5AJ2hI5Ytk53VXSTbJjCi4EI9pCgA2lH4osniIdzs4HD8cjbhP9qTTxRLQz0eXCVajWoKVWrrxUmEVvNiqH1IuRqFBO_AQSCZ75SsR60yr3U7yLvMImszpaC5WHTC-_aTfQrr6sRYnquM0NAKwi5sHES-yZdMH0C-ZvNvVPsRkKGoI12js8HHGEdnhVFw') }}" />
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-transparent"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-6">
            <div class="mb-16">
                <p {{ $attributes ?? '' }} class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/50 mb-4">{{ $content['geral']['description_10'] ?? 'Upcoming Events' }}</p>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <h2 {{ $attributes ?? '' }} class="font-display italic-bold text-5xl md:text-8xl leading-none text-white uppercase">{{ $content['geral']['heading_8'] ?? 'MARATONA BRASÍLIA' }}</h2>
                    <p {{ $attributes ?? '' }} class="text-white/60 max-w-xs text-sm leading-relaxed mb-2">{{ $content['geral']['description_11'] ?? 'The Maratona Brasília is more than a race, its a celebration of culture, endurance, and
                        togetherness in the heart of Brazil.' }}</p>
                </div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-8 md:p-12 space-y-8">
                <div class="flex flex-col md:flex-row justify-between items-center py-4 border-b border-white/10 gap-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-white/40">Location:</span>
                    <span class="font-display italic text-lg text-white uppercase">Eixo Monumental, Brasília, DF</span>
                </div>
                <div class="flex flex-col md:flex-row justify-between items-center py-4 border-b border-white/10 gap-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-white/40">Category:</span>
                    <span class="font-display italic text-lg text-white uppercase">5K, 10K, 21K, 42K</span>
                </div>
                <div class="flex flex-col md:flex-row justify-between items-center py-4 border-b border-white/10 gap-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-white/40">Date:</span>
                    <span class="font-display italic text-lg text-white uppercase text-center">Sunday, November 23,
                        2025</span>
                </div>
                <div class="flex flex-col md:flex-row justify-between items-center py-4 gap-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-white/40">Time Start:</span>
                    <span class="font-display italic text-lg text-white uppercase">06:00 AM</span>
                </div>
                <div class="pt-8 flex justify-center">
                    <button
                        class="bg-white text-black px-12 py-3 font-display text-sm italic uppercase tracking-wider hover:bg-primary hover:text-white transition-all">Join
                        Now</button>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-background-light dark:bg-background-dark py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <p {{ $attributes ?? '' }} class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-4">{{ $content['geral']['description_12'] ?? 'Community Events' }}</p>
                <h2 {{ $attributes ?? '' }}
                    class="font-display italic-bold text-5xl md:text-7xl leading-tight text-slate-900 dark:text-white uppercase">{{ $content['geral']['heading_9'] ?? 'RUN THE DATE OWN THE MILES' }}</h2>
                <p {{ $attributes ?? '' }} class="text-slate-500 dark:text-zinc-400 mt-6 max-w-xl mx-auto text-sm leading-relaxed">{{ $content['geral']['description_13'] ?? 'Discover our
                    upcoming runs, from casual weekend jogs to exciting city races across the Federal District.' }}</p>
            </div>
            <div class="space-y-4">
                <div
                    class="group flex flex-col md:flex-row md:items-center justify-between p-6 bg-white dark:bg-zinc-900/40 border border-zinc-200 dark:border-white/5 hover:border-primary/50 transition-all">
                    <h3 {{ $attributes ?? '' }} class="font-display italic text-xl text-slate-900 dark:text-white uppercase mb-4 md:mb-0">{{ $content['geral']['heading_10'] ?? 'Weekend Fun Run' }}</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">5K</span>
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">October
                            13, 2025</span>
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">Parque
                            da Cidade</span>
                    </div>
                </div>
                <div
                    class="group flex flex-col md:flex-row md:items-center justify-between p-6 bg-white dark:bg-zinc-900/40 border border-zinc-200 dark:border-white/5 hover:border-primary/50 transition-all">
                    <h3 {{ $attributes ?? '' }} class="font-display italic text-xl text-slate-900 dark:text-white uppercase mb-4 md:mb-0">{{ $content['geral']['heading_11'] ?? 'Night
                        Run Legacy' }}</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">10K</span>
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">October
                            26, 2025</span>
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">Lago
                            Sul</span>
                    </div>
                </div>
                <div
                    class="group flex flex-col md:flex-row md:items-center justify-between p-6 bg-white dark:bg-zinc-900/40 border border-zinc-200 dark:border-white/5 hover:border-primary/50 transition-all">
                    <h3 {{ $attributes ?? '' }} class="font-display italic text-xl text-slate-900 dark:text-white uppercase mb-4 md:mb-0">{{ $content['geral']['heading_12'] ?? 'Cerrado Trail Challenge' }}</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">12K</span>
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">1,000M
                            EG</span>
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">November
                            10, 2025</span>
                        <span
                            class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-slate-500 dark:text-zinc-400 uppercase rounded-full border border-zinc-200 dark:border-white/10">Chapada</span>
                    </div>
                </div>
            </div>
            <div class="mt-12 text-center">
                <button
                    class="bg-slate-200 dark:bg-zinc-800 text-slate-900 dark:text-white px-8 py-3 font-bold text-xs uppercase tracking-widest hover:bg-primary hover:text-white transition-all rounded-full">See
                    More</button>
            </div>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-6 mb-24">
        <div
            class="grid md:grid-cols-2 bg-white dark:bg-zinc-900 rounded-[2rem] overflow-hidden border border-zinc-200 dark:border-white/5">
            <div class="p-12 md:p-20 flex flex-col justify-center items-start">
                <h2 {{ $attributes ?? '' }}
                    class="font-display italic-bold text-5xl md:text-7xl leading-none text-slate-900 dark:text-white uppercase mb-6">{{ $content['geral']['heading_13'] ?? 'READY TO START YOUR JOURNEY?' }}</h2>
                <p {{ $attributes ?? '' }} class="text-slate-500 dark:text-zinc-400 mb-10 max-w-sm leading-relaxed">{{ $content['geral']['description_14'] ?? 'Be part of a growing running community that supports you every step of the way. No matter your pace,
                    youre welcome here.' }}</p>
                <div class="flex flex-wrap gap-4">
                    <button
                        class="px-8 py-3 border-2 border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-white font-bold text-[10px] uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all rounded-full">Sign
                        Up Free</button>
                    <button
                        class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-black font-bold text-[10px] uppercase tracking-widest hover:bg-primary hover:text-white transition-all rounded-full">Join
                        Our Next Run</button>
                </div>
            </div>
            <div class="relative h-96 md:h-auto min-h-[400px]">
                <img alt="Runners high five" class="absolute inset-0 w-full h-full object-cover"
                    src="{{ asset($content['geral']['image_8'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZ9WBEoyyygSQIP1u7F_IE-S0FSMOq2i1vTyhllByc_qOb9CJkL6L5dQDd3FakLL2tQcMaPLdHPmQ6q_Qea-Y9dYYnp8NgkemZq3roOMa3VNOOTelUwseQQKFodvM4YGuItx_NsfhUC77T6EhBOMPkC2vjwDGN6M6Bd6wsWBQyrpahNMPTEQkpfzjZQC1FHrDwE4fOx29PxJ-Ys2T4BSVYSuXwpgmfkcWf7nDPGdxDaocHeUjSD40Iyvowfwu8oDdiQeY-J4aif_M') }}" />
                <div class="absolute inset-0 bg-primary/20 mix-blend-multiply"></div>
            </div>
        </div>
    </section>
    <footer class="bg-slate-100 dark:bg-[#080808] pt-24 pb-12 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="relative mb-24">
                <span
                    class="absolute -top-12 -left-4 font-display italic-bold text-[12rem] md:text-[20rem] text-slate-200 dark:text-white/[0.03] pointer-events-none uppercase tracking-tighter">BRASILIA</span>
                <div
                    class="relative z-10 grid grid-cols-2 md:grid-cols-3 gap-12 text-sm font-bold uppercase tracking-widest">
                    <div class="space-y-4">
                        <a class="block text-slate-600 dark:text-zinc-500 hover:text-primary transition-colors"
                            href="#">Home</a>
                        <a class="block text-slate-600 dark:text-zinc-500 hover:text-primary transition-colors"
                            href="#">Event</a>
                    </div>
                    <div class="space-y-4">
                        <a class="block text-slate-600 dark:text-zinc-500 hover:text-primary transition-colors"
                            href="#">Gallery</a>
                        <a class="block text-slate-600 dark:text-zinc-500 hover:text-primary transition-colors"
                            href="#">About</a>
                    </div>
                    <div class="space-y-4">
                        <a class="block text-slate-600 dark:text-zinc-500 hover:text-primary transition-colors"
                            href="#">Support</a>
                    </div>
                </div>
            </div>
            <div
                class="pt-12 border-t border-slate-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between gap-8">
                <p {{ $attributes ?? '' }} class="text-[10px] font-bold text-slate-400 dark:text-zinc-600 uppercase tracking-widest">{{ $content['geral']['description_15'] ?? 'Copyright ©
                    2025 Brasília Run Community®' }}</p>
                <div class="flex items-center gap-6">
                    <a class="text-slate-400 dark:text-zinc-600 hover:text-primary transition-colors" href="#"><span
                            class="material-symbols-outlined">camera</span></a>
                    <a class="text-slate-400 dark:text-zinc-600 hover:text-primary transition-colors" href="#"><span
                            class="material-symbols-outlined">chat</span></a>
                    <a class="text-slate-400 dark:text-zinc-600 hover:text-primary transition-colors font-display italic text-lg"
                        href="#">X</a>
                </div>
                <div
                    class="flex items-center gap-8 text-[10px] font-bold text-slate-400 dark:text-zinc-600 uppercase tracking-widest">
                    <a class="hover:text-primary" href="#">Privacy Policy</a>
                    <a class="hover:text-primary" href="#">Terms of Conditions</a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>