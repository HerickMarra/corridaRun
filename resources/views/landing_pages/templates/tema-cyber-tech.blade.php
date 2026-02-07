<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['geral']['title'] ?? 'Cyber Tech Run' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" />
    <style>
        :root {
            --neon:
                {{ $content['geral']['accent_color'] ?? '#00ff41' }}
            ;
        }

        body {
            font-family: 'JetBrains Mono', monospace;
            background-color: #050505;
            color: #fff;
        }

        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
        }

        .text-neon {
            color: var(--neon);
            text-shadow: 0 0 10px var(--neon);
        }

        .bg-neon {
            background-color: var(--neon);
        }

        .border-neon {
            border-color: var(--neon);
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px border-white/5;
        }

        .scanline {
            width: 100%;
            height: 2px;
            background: var(--neon);
            opacity: 0.1;
            position: absolute;
            animation: scan 4s linear infinite;
            z-index: 40;
        }

        @keyframes scan {
            0% {
                top: 0;
            }

            100% {
                top: 100%;
            }
        }

        .grid-bg {
            background-image: linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
        }
    </style>
</head>

<body class="antialiased grid-bg">
    <div class="scanline"></div>

    <!-- Nav -->
    <nav class="fixed top-0 w-full z-50 p-6 border-b border-white/5 glass flex justify-between items-center">
        <div class="font-orbitron font-black text-xl tracking-[0.2em]">CYBER<span class="text-neon">SPRINT</span></div>
        <div class="bg-neon/10 border border-neon/30 p-1 rounded-sm">
            <a href="#register"
                class="bg-neon text-black px-6 py-2 font-bold text-[10px] uppercase tracking-widest hover:brightness-125 transition-all block">
                {{ $content['geral']['cta_text'] ?? 'CONNECT' }}
            </a>
        </div>
    </nav>

    <!-- Hero -->
    <header class="relative min-h-screen flex items-center justify-center pt-24 px-6 overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-neon/10 to-transparent"></div>

        <div class="relative z-10 text-center space-y-12">
            <div class="inline-block p-1 border border-neon/20 mb-8">
                <p
                    class="text-[10px] tracking-widest font-bold px-4 py-1 border border-neon/50 text-neon animate-pulse uppercase">
                    Status: Protocol Active • Version 2.0.77
                </p>
            </div>
            <h1 class="font-orbitron font-black text-6xl md:text-9xl leading-none tracking-tighter uppercase relative">
                {{ $content['geral']['title'] ?? 'CYBER SPRINT' }}
                <span
                    class="absolute -top-10 -right-10 text-xs font-mono text-zinc-700 hidden lg:block">[DATA_BLOCK_04]</span>
            </h1>
            <p class="text-zinc-500 max-w-2xl mx-auto text-sm leading-relaxed border-x border-white/5 px-12">
                {{ $content['geral']['intro_text'] ?? 'The future of racing is here. Holographic pacer, data-driven track analysis, and real-time biomechanical feedback.' }}
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto pt-12">
                <div class="glass p-6 text-left border-l-2 border-neon">
                    <p class="text-[10px] text-zinc-600 mb-2 font-bold">CORE</p>
                    <p class="font-orbitron text-sm">HOLOGRAPHIC</p>
                </div>
                <div class="glass p-6 text-left border-l-2 border-zinc-700">
                    <p class="text-[10px] text-zinc-600 mb-2 font-bold">SYNC</p>
                    <p class="font-orbitron text-sm">BIO-DATA</p>
                </div>
                <div class="glass p-6 text-left border-l-2 border-zinc-700">
                    <p class="text-[10px] text-zinc-600 mb-2 font-bold">GRID</p>
                    <p class="font-orbitron text-sm">DOWNTOWN</p>
                </div>
                <div class="glass p-6 text-left border-l-2 border-neon">
                    <p class="text-[10px] text-zinc-600 mb-2 font-bold">HEAT</p>
                    <p class="font-orbitron text-sm">ULTRA_LITE</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Specs Section -->
    <section class="py-40 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-32 items-center">
            <div class="space-y-12">
                <h2 class="font-orbitron text-4xl md:text-6xl text-white uppercase leading-none">SYSTEM <span
                        class="text-neon underline underline-offset-8">SPECIFICATIONS</span></h2>
                <div class="space-y-8">
                    <div class="flex items-center gap-6 group">
                        <div class="size-1 w-12 bg-zinc-800 group-hover:bg-neon transition-all duration-500"></div>
                        <h4 class="font-orbitron text-xl uppercase tracking-widest">
                            {{ $content['geral']['module_1'] ?? 'Neural Sync' }}</h4>
                    </div>
                    <div class="flex items-center gap-6 group">
                        <div class="size-1 w-12 bg-zinc-800 group-hover:bg-neon transition-all duration-500"></div>
                        <h4 class="font-orbitron text-xl uppercase tracking-widest">
                            {{ $content['geral']['module_2'] ?? 'Augmented Reality' }}</h4>
                    </div>
                    <div class="flex items-center gap-6 group">
                        <div class="size-1 w-12 bg-zinc-800 group-hover:bg-neon transition-all duration-500"></div>
                        <h4 class="font-orbitron text-xl uppercase tracking-widest">
                            {{ $content['geral']['module_3'] ?? 'Plasma Hydration' }}</h4>
                    </div>
                </div>
            </div>
            <div class="relative group">
                <div
                    class="absolute inset-0 bg-neon/10 blur-3xl opacity-20 -z-10 group-hover:opacity-40 transition-opacity">
                </div>
                <div class="p-4 border border-white/5 glass group-hover:border-neon/20 transition-all duration-1000">
                    <img src="https://images.unsplash.com/photo-1549438343-98282367ea1b?auto=format&fit=crop&q=80&w=800"
                        class="w-full grayscale brightness-125 opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-1000"
                        alt="Futuristic Tech">
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section id="register" class="py-48 px-6 text-center">
        <div class="max-w-5xl mx-auto py-24 glass border border-white/10 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 font-mono text-[8px] text-zinc-800">01010111 01001001 01001110</div>
            <h2 class="font-orbitron text-5xl md:text-8xl mb-12 tracking-tighter italic">LOCKED & <span
                    class="text-neon">LOADED.</span></h2>
            <div class="inline-block p-1 bg-neon shadow-[0_0_30px_rgba(0,255,65,0.3)]">
                <a href="/register"
                    class="bg-black text-neon px-20 py-8 font-black text-2xl uppercase tracking-[0.2em] font-orbitron hover:bg-zinc-900 transition-all block">
                    {{ $content['geral']['cta_text'] ?? 'RUN SYSTEM' }}
                </a>
            </div>
            <div class="mt-12 flex justify-center gap-6 opacity-20 text-[8px] font-bold uppercase tracking-widest">
                <span>Secure_Connection_Established</span>
                <span>•</span>
                <span>Ready_For_Deployment</span>
            </div>
        </div>
    </section>

    <footer class="py-32 border-t border-white/5 text-center">
        <div class="font-orbitron text-xl mb-12 tracking-widest underline decoration-neon underline-offset-8">
            CYBER_UNIT_0.1</div>
        <p class="text-zinc-700 text-[8px] font-bold uppercase tracking-[0.8em] mb-12 italic">Synthetic Performance
            Division • Neural Gear Co.</p>
        <div class="flex justify-center gap-12 text-[10px] font-bold uppercase tracking-widest text-zinc-500">
            <a href="#" class="hover:text-neon transition-colors">Manifesto</a>
            <a href="#" class="hover:text-neon transition-colors">OS_Updates</a>
            <a href="#" class="hover:text-neon transition-colors">Network</a>
        </div>
    </footer>
</body>

</html>