<!DOCTYPE html>
<html class="light" lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title') - {{ config('app.name', 'RunPace') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d59f2",
                        "background-light": "#ffffff",
                        "background-dark": "#0f172a",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.75rem",
                        "lg": "1.5rem",
                        "xl": "2.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        body {
            font-family: 'Space Grotesk', sans-serif;
        }
        .gradient-button {
            background: linear-gradient(135deg, #0d59f2 0%, #3b82f6 100%);
        }
        .hero-text-shadow {
            text-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }
        input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        .responsive-title {
            font-size: clamp(2rem, 4.5vw + 0.5rem, 4.2rem);
            line-height: 1.05;
        }
        .golden-hour-overlay {
            background: linear-gradient(
                135deg,
                rgba(15, 23, 42, 0.4) 0%,
                rgba(255, 140, 0, 0.15) 50%,
                rgba(255, 255, 255, 0) 100%
            );
        }
        .lens-flare-subtle {
            position: absolute;
            top: -10%;
            right: -10%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,165,0,0.05) 50%, transparent 70%);
            filter: blur(40px);
            pointer-events: none;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-white dark:bg-background-dark min-h-screen overflow-hidden">
    <header
        class="fixed top-0 left-0 w-full z-50 px-6 md:px-10 py-6 flex items-center justify-between pointer-events-none">
        <a href="/" class="flex items-center gap-2 text-white pointer-events-auto">
            <div class="size-7 text-white drop-shadow-lg">
                <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd"
                        d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z"
                        fill="currentColor" fill-rule="evenodd"></path>
                </svg>
            </div>
            <h2 class="text-lg font-bold tracking-[0.2em] uppercase drop-shadow-lg">RUNPACE</h2>
        </a>
    </header>

    <main class="flex min-h-screen w-full relative">
        <section class="hidden md:flex md:w-[55%] lg:w-[62%] relative overflow-hidden bg-slate-900">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 scale-105"
                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAKAqsbxR5gCapWHOh-OsDFzKzYSyvSN53mWXLdKTL5d4O9BhKSikAsD_oRNIlM9zHbUEMK2xSyBPjQJfriDNIQ0_NsEhkNoVGEQRG8o16LKkDKVjg_f0hIhp-T8CpQqSTRyYcTxgdRRCePuxplwWFH6m3WmNIurJm2Nh2ZbBFhRmLVE0SgTqeoiGJFaReTGHt_MEChzBP5zLL2CDCVW9xbhGlws7uqVtkOxRG8bYAcOX6WEO3Ys8MTaxvrzGV0ccLRiJrTLMr9cE4');">
                <div class="absolute inset-0 golden-hour-overlay"></div>
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="lens-flare-subtle"></div>
            </div>
            <div class="relative z-10 flex flex-col justify-center px-12 lg:px-20 h-full">
                <div class="max-w-2xl">
                    <h1
                        class="text-white hero-text-shadow tracking-tighter font-extrabold uppercase responsive-title mb-6">
                        @if(View::hasSection('hero-title'))
                            @yield('hero-title')
                        @else
                            {!! 'SUA PRÓXIMA <br/> SUPERAÇÃO <br/> COMEÇA AQUI.' !!}
                        @endif
                    </h1>
                    <div class="h-1 w-24 bg-white/60 mb-8 rounded-full"></div>
                    <p class="text-base lg:text-lg text-white/90 font-medium max-w-md leading-relaxed drop-shadow-md">
                        @if(View::hasSection('hero-description'))
                            @yield('hero-description')
                        @else
                            Faça parte da maior comunidade de corredores e encontre os melhores eventos.
                        @endif
                    </p>
                </div>
            </div>
            <div class="absolute bottom-10 left-12 lg:left-20 z-10">
                <div class="flex items-center gap-4 text-white/50 text-[10px] tracking-[0.3em] font-bold uppercase">
                    <span>Performance</span>
                    <span class="size-1 bg-white/30 rounded-full"></span>
                    <span>Comunidade</span>
                    <span class="size-1 bg-white/30 rounded-full"></span>
                    <span>Exclusividade</span>
                </div>
            </div>
        </section>

        <section
            class="w-full md:w-[45%] lg:w-[38%] flex flex-col justify-center items-center bg-white dark:bg-slate-950 px-8 sm:px-12 lg:px-16 xl:px-20 py-8 relative z-20">
            @yield('content')
        </section>
    </main>

    @stack('scripts')
</body>

</html>