@extends('layouts.auth')

@section('title', 'Login de Atleta')

@section('content')
    <div class="w-full max-w-[360px]">
        <div class="mb-8">
            <h3 class="text-slate-900 dark:text-white text-2xl font-bold tracking-tight mb-1.5">Bem-vindo de volta</h3>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Acesse sua conta de atleta</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 ml-0.5">E-mail</label>
                <input name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 h-11 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm font-medium"
                    placeholder="nome@atleta.com" type="email" />
            </div>
            <div class="space-y-1.5">
                <div class="flex justify-between items-center px-0.5">
                    <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400">Senha</label>
                    <a class="text-[10px] font-bold uppercase tracking-widest text-primary hover:opacity-80 transition-opacity"
                        href="#">Esquecer senha</a>
                </div>
                <input name="password" required
                    class="w-full px-4 h-11 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm font-medium"
                    placeholder="••••••••" type="password" />
            </div>
            <div class="flex items-center gap-3 px-0.5 py-1">
                <input name="remember"
                    class="rounded text-primary focus:ring-primary border-slate-200 w-4 h-4 cursor-pointer" id="remember"
                    type="checkbox" />
                <label class="text-xs font-medium text-slate-500 cursor-pointer select-none" for="remember">Manter
                    conectado</label>
            </div>
            <button type="submit"
                class="gradient-button w-full h-12 rounded-full text-white font-bold text-xs uppercase tracking-[0.15em] shadow-xl shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all duration-300 mt-2">
                Entrar
            </button>
        </form>

        <div class="mt-8">
            <div class="relative flex items-center mb-5">
                <div class="flex-grow border-t border-slate-100 dark:border-slate-800"></div>
                <span class="flex-shrink mx-4 text-[9px] font-bold text-slate-300 uppercase tracking-[0.25em]">Acesso
                    Rápido</span>
                <div class="flex-grow border-t border-slate-100 dark:border-slate-800"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <button
                    class="flex items-center justify-center gap-2 h-10 border border-slate-200 dark:border-slate-800 rounded-full hover:bg-slate-50 dark:hover:bg-slate-900 transition-all group">
                    <svg class="size-4" viewBox="0 0 24 24">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4"></path>
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853"></path>
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05"></path>
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335"></path>
                    </svg>
                    <span
                        class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 group-hover:text-slate-900">Google</span>
                </button>
                <button
                    class="flex items-center justify-center gap-2 h-10 border border-slate-200 dark:border-slate-800 rounded-full hover:bg-slate-50 dark:hover:bg-slate-900 transition-all group">
                    <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C4.16 16.37 3.2 9.5 5.92 6.08c1.36-1.7 3.07-1.87 4.15-1.18 1.13.72 1.63.69 2.84 0 1.03-.6 3.03-.78 4.4 1 2.85 3.53 1.98 9.54-.26 14.38zM12.03 4.8c-.24-1.29.35-2.5 1.4-3.23 1.34-.94 2.5-.78 3.1 0 .2.23.4.67.2 1.3-.2 1.3-.9 2.3-1.9 2.9-1 .6-2.1.2-2.8-.97z">
                        </path>
                    </svg>
                    <span
                        class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 group-hover:text-slate-900">Apple</span>
                </button>
            </div>
        </div>

        <div class="mt-10 text-center">
            <p class="text-xs font-medium text-slate-400">
                Novo por aqui?
                <a class="text-primary font-bold hover:underline ml-1" href="{{ route('register') }}">Criar conta</a>
            </p>
            <div class="mt-8 flex justify-center gap-6">
                <a class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-300 hover:text-primary transition-colors"
                    href="#">Suporte</a>
                <a class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-300 hover:text-primary transition-colors"
                    href="#">Termos</a>
                <a class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-300 hover:text-primary transition-colors"
                    href="#">Privacidade</a>
            </div>
        </div>
    </div>
@endsection