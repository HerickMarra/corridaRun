<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Sisters Esportes - Supere Seus Limites</title>

    <!-- Scripts and Fonts from layouthome.html -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries,typography"></script>
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
                        "primary": "#0052FF", "secondary": "#111111",
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
                @apply bg-background-light text-secondary;
            }
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .hero-gradient-overlay {
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.5) 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
        }
        .card-shadow-hover {
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.12);
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
                    <h2 class="text-xl font-bold tracking-tight text-secondary uppercase italic hidden sm:block">
                        Sisters Esportes
                    </h2>
                </a>
                <nav class="hidden md:flex items-center gap-8">
                    <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                        href="/">Eventos</a>
                    <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                        href="{{ route('calendar') }}">Calendário</a>
                    <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                        href="{{ route('partner') }}">Seja um Parceiro</a>
                    @auth
                        <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                            href="{{ route('client.dashboard') }}">Área do
                            Corredor</a>
                        <a class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors"
                            href="{{ route('client.registrations') }}">Minhas
                            Inscrições</a>
                    @endauth
                </nav>
            </div>
            <div class="flex items-center gap-6">
                @auth
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
                @else
                    <a href="/login"
                        class="bg-secondary hover:bg-black text-white px-6 py-2 rounded-full text-sm font-bold transition-all transform active:scale-95">
                        Entrar
                    </a>
                @endauth
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
            @auth
                <a class="text-base font-bold text-slate-600 hover:text-primary transition-colors py-2 border-b border-slate-50"
                    href="{{ route('client.dashboard') }}">Área do Corredor</a>
                <a class="text-base font-bold text-slate-600 hover:text-primary transition-colors py-2"
                    href="{{ route('client.registrations') }}">Minhas Inscrições</a>
            @endauth
        </div>
    </header>

    @yield('content')

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
                <div class="flex gap-4">
                    <!-- Social Icons -->
                </div>
            </div>
            <div>
                <h4 class="text-secondary font-black uppercase tracking-[0.2em] text-[10px] mb-8">Plataforma</h4>
                <ul class="space-y-4 text-slate-500 text-sm font-semibold">
                    <li><a class="hover:text-primary transition-colors" href="#">Sobre Nós</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('calendar') }}">Calendário</a>
                    </li>
                    @auth
                        <li><a class="hover:text-primary transition-colors"
                                href="{{ route('client.registrations') }}">Minhas Inscrições</a></li>
                    @endauth
                    <li><a class="hover:text-primary transition-colors" href="#">Resultados</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-secondary font-black uppercase tracking-[0.2em] text-[10px] mb-8">Newsletter</h4>
                <div class="flex flex-col gap-4">
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Receba avisos de novas etapas e
                        pré-vendas exclusivas.</p>
                    <div class="relative">
                        <input
                            class="w-full bg-slate-50 border border-slate-200 rounded-full px-5 py-4 text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                            placeholder="Seu e-mail" type="email" />
                        <button
                            class="absolute right-2 top-2 bg-secondary text-white font-bold px-5 py-2 rounded-full text-[10px] uppercase tracking-widest hover:bg-black transition-all">Assinar</button>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="max-w-[1440px] mx-auto mt-24 pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">© {{ date('Y') }}
                {{ strtoupper(config('app.name', 'Run Platform')) }}. Todos os direitos reservados.
            </p>
            <div class="flex gap-8 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                <a class="hover:text-secondary transition-colors" href="#">Termos de Uso</a>
                <a class="hover:text-secondary transition-colors" href="#">Privacidade</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>