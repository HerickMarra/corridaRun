<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'CORREDOR HUB') - Sisters Esportes</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0052FF",
                        "secondary": "#111111",
                        "background-light": "#FFFFFF",
                        "background-soft": "#F9FAFB",
                        "card-light": "#FFFFFF",
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
        @layer base {
            body {
                font-family: 'Space Grotesk', sans-serif;
                @apply bg-white text-secondary;
            }
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .card-shadow {
            box-shadow: 0 10px 40px -15px rgba(0, 0, 0, 0.05);
        }
        .card-shadow-hover {
            box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.1);
        }
        .timeline-line {
            background: linear-gradient(180deg, #0052FF 0%, #E2E8F0 100%);
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen overflow-x-hidden antialiased">
    <header x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 border-b border-slate-100 glass-nav">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 flex items-center justify-between h-20">
            <div class="flex items-center gap-4 md:gap-12">
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-secondary p-2 -ml-2">
                    <span class="material-symbols-outlined" x-show="!mobileMenuOpen">menu</span>
                    <span class="material-symbols-outlined" x-show="mobileMenuOpen" x-cloak>close</span>
                </button>

                <a href="/" class="flex items-center gap-2">
                    <div class="size-7 text-primary">
                        <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight text-secondary uppercase italic hidden sm:block">Sisters
                        Esportes</h2>
                </a>
                <nav class="hidden md:flex items-center gap-8">
                    <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                        href="/">Eventos</a>
                    <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                        href="{{ route('calendar') }}">Calendário</a>
                    <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                        href="{{ route('partner') }}">Seja um Parceiro</a>
                    <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                        href="{{ route('client.dashboard') }}">Área do
                        Corredor</a>
                    <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                        href="{{ route('client.registrations') }}">Minhas Inscrições</a>
                </nav>
            </div>
            <div class="flex items-center gap-6">
                <button class="relative">
                    <span class="material-symbols-outlined text-slate-400">notifications</span>
                    <span class="absolute top-0 right-0 size-2 bg-primary rounded-full border-2 border-white"></span>
                </button>
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden">
                        <img alt="Avatar" class="w-full h-full object-cover"
                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0052FF&color=fff" />
                    </div>
                    <span class="text-sm font-bold hidden sm:block">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-4">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition x-cloak
            class="md:hidden absolute top-20 left-0 w-full bg-white border-b border-slate-100 p-6 flex flex-col gap-4 shadow-xl">
            <a class="text-base font-bold text-slate-600 hover:text-primary transition-colors py-2 border-b border-slate-50"
                href="/">Eventos</a>
            <a class="text-base font-bold text-slate-600 hover:text-primary transition-colors py-2 border-b border-slate-50"
                href="{{ route('calendar') }}">Calendário</a>
            <a class="text-base font-bold text-slate-600 hover:text-primary transition-colors py-2 border-b border-slate-50"
                href="{{ route('partner') }}">Seja um Parceiro</a>
            <a class="text-base font-bold text-slate-600 hover:text-primary transition-colors py-2 border-b border-slate-50"
                href="{{ route('client.dashboard') }}">Área do Corredor</a>
            <a class="text-base font-bold text-slate-600 hover:text-primary transition-colors py-2"
                href="{{ route('client.registrations') }}">Minhas Inscrições</a>
        </div>
    </header>

    <main class="pt-32 pb-24 px-6 lg:px-12 bg-white">
        <div class="max-w-[1440px] mx-auto">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-slate-100 py-24 px-6 lg:px-12">
        <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-16">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-8">
                    <div class="size-8 text-primary">
                        <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black tracking-tight text-secondary uppercase italic">Sisters Esportes</h2>
                </div>
                <p class="text-slate-500 font-medium max-w-sm mb-10 leading-relaxed">
                    A maior comunidade de corredores do Brasil. Performance, tecnologia e conexões reais em cada
                    quilômetro.
                </p>
            </div>
            <div>
                <h4 class="text-secondary font-black uppercase tracking-[0.2em] text-[10px] mb-8">Área do Atleta</h4>
                <ul class="space-y-4 text-slate-500 text-sm font-semibold">
                    <li><a class="hover:text-primary transition-colors" href="#">Minhas Provas</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Certificados</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Treinamento</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Ajuda</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-secondary font-black uppercase tracking-[0.2em] text-[10px] mb-8">Redes Sociais</h4>
                <div class="flex gap-4">
                    <div
                        class="size-11 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center hover:bg-primary hover:text-white transition-all cursor-pointer">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="max-w-[1440px] mx-auto mt-24 pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">© {{ date('Y') }} RUN PLATFORM.
                HUB DO ATLETA.</p>
            <div class="flex gap-8 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                <a class="hover:text-secondary transition-colors" href="#">Termos</a>
                <a class="hover:text-secondary transition-colors" href="#">Privacidade</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>