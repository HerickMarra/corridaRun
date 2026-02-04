@extends('layouts.auth')

@section('title', 'Criar Conta de Atleta')

@section('hero-title')
    JUNTE-SE À <br /> <span
        class="text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-orange-100">ELITE</span> <br /> DO
    RUNNING.
@endsection

@section('hero-description', 'Crie sua conta e tenha acesso a eventos exclusivos, acompanhamento de performance e uma comunidade apaixonada.')

@section('content')
    <div class="w-full max-w-[360px]">
        <div class="mb-8">
            <h3 class="text-slate-900 dark:text-white text-2xl font-bold tracking-tight mb-1.5">Criar conta</h3>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Inicie sua jornada como atleta</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 ml-0.5">Nome Completo</label>
                <input name="name" value="{{ old('name') }}" required autofocus
                    class="w-full px-4 h-11 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm font-medium"
                    placeholder="Seu nome" type="text" />
            </div>
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 ml-0.5">E-mail</label>
                <input name="email" value="{{ old('email') }}" required
                    class="w-full px-4 h-11 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm font-medium"
                    placeholder="nome@atleta.com" type="email" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 ml-0.5">Senha</label>
                    <input name="password" required
                        class="w-full px-4 h-11 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm font-medium"
                        placeholder="••••••••" type="password" />
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 ml-0.5">Confirmar</label>
                    <input name="password_confirmation" required
                        class="w-full px-4 h-11 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm font-medium"
                        placeholder="••••••••" type="password" />
                </div>
            </div>

            <p class="text-[10px] text-slate-400 font-medium px-1">
                Ao se registrar, você concorda com nossos <a href="#" class="text-primary font-bold">Termos</a> e <a
                    href="#" class="text-primary font-bold">Privacidade</a>.
            </p>

            <button type="submit"
                class="gradient-button w-full h-12 rounded-full text-white font-bold text-xs uppercase tracking-[0.15em] shadow-xl shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all duration-300 mt-2">
                Criar Minha Conta
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-50 pt-8">
            <p class="text-xs font-medium text-slate-400">
                Já possui uma conta?
                <a class="text-primary font-bold hover:underline ml-1" href="{{ route('login') }}">Fazer login</a>
            </p>
        </div>
    </div>
@endsection