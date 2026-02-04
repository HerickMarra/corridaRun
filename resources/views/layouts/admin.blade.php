<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Admin Dashboard - {{ config('app.name', 'RunPace') }}</title>
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
                        "admin-bg": "#f8fafc",
                        "admin-card": "#ffffff",
                        "sidebar-bg": "#0f172a",
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        body { font-family: 'Space Grotesk', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .sidebar-active { background: rgba(255, 255, 255, 0.1); border-left: 4px solid #0d59f2; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Override default browser alert with SweetAlert2
        window.alert = function (message) {
            Swal.fire({
                title: 'Notificação',
                text: message,
                icon: 'info',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#0d59f2',
                customClass: {
                    container: 'swal2-premium-container',
                    popup: 'rounded-3xl border-none p-10',
                    title: 'text-xl font-bold uppercase italic tracking-tighter text-slate-800',
                    content: 'text-sm font-medium text-slate-500',
                    confirmButton: 'bg-primary text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/20 outline-none border-none'
                },
                buttonsStyling: false,
                backdrop: `rgba(15, 23, 42, 0.6)`
            });
        };

        // Helper for custom toasts or other alerts
        window.toast = function (message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }
    </script>
</head>

<body class="bg-admin-bg min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-sidebar-bg text-white hidden lg:flex flex-col fixed h-full z-50">
        <div class="p-6 flex items-center gap-3">
            <div class="size-8 text-white">
                <svg fill="currentColor" viewBox="0 0 48 48">
                    <path
                        d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z">
                    </path>
                </svg>
            </div>
            <span class="text-xl font-bold tracking-widest uppercase">RUNPACE</span>
        </div>

        <nav class="flex-grow mt-6">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-4 px-6 py-4 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'text-white/70 hover:text-white' }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-sm font-semibold">Dashboard</span>
            </a>
            <a href="{{ route('admin.corridas.index') }}"
                class="flex items-center gap-4 px-6 py-4 hover:bg-white/5 transition-all {{ request()->routeIs('admin.corridas.*') ? 'sidebar-active' : 'text-white/70 hover:text-white' }}">
                <span class="material-symbols-outlined">directions_run</span>
                <span class="text-sm font-semibold">Corridas</span>
            </a>
            <a href="{{ route('admin.athletes.index') }}"
                class="flex items-center gap-4 px-6 py-4 hover:bg-white/5 transition-all {{ request()->routeIs('admin.athletes.*') ? 'sidebar-active' : 'text-white/70 hover:text-white' }}">
                <span class="material-symbols-outlined">groups</span>
                <span class="text-sm font-semibold">Atletas</span>
            </a>
            <a href="{{ route('admin.sales.index') }}"
                class="flex items-center gap-4 px-6 py-4 hover:bg-white/5 transition-all {{ request()->routeIs('admin.sales.*') ? 'sidebar-active' : 'text-white/70 hover:text-white' }}">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="text-sm font-semibold">Vendas</span>
            </a>
            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-4 px-6 py-4 hover:bg-white/5 transition-all {{ request()->routeIs('admin.settings.*') ? 'sidebar-active' : 'text-white/70 hover:text-white' }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-sm font-semibold">Configurações</span>
            </a>
        </nav>

        <div class="p-6 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-4 text-white/50 hover:text-red-400 transition-all text-sm font-bold uppercase tracking-widest">
                    <span class="material-symbols-outlined">logout</span>
                    Sair
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow lg:ml-64 p-8">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Olá, {{ auth()->user()->name }}</h1>
                <p class="text-slate-500 text-sm font-medium">Bem-vindo ao centro de comando da RunPace.</p>
            </div>
            <div class="flex items-center gap-6">
                <button class="relative size-10 flex items-center justify-center bg-white rounded-full shadow-sm">
                    <span class="material-symbols-outlined text-slate-400">notifications</span>
                    <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-primary uppercase tracking-widest">
                            {{ auth()->user()->role->value }}
                        </p>
                    </div>
                    <div
                        class="size-11 bg-primary rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>