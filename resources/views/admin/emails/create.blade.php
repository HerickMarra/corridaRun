@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.emails.index') }}"
            class="size-12 rounded-2xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 transition-all text-decoration-none">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Novo Modelo de <span
                    class="text-primary">E-mail</span></h2>
            <p class="text-slate-500 text-sm font-medium">Crie um novo modelo para suas campanhas de marketing.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('admin.emails.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Nome do
                            Modelo</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all"
                            placeholder="Ex: Convite Corrida Noturna">
                        @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Assunto do
                            E-mail</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                            class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all"
                            placeholder="Ex: Você está convidado para a nossa próxima prova!">
                        @error('subject') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Descrição
                            (Interna)</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all"
                            placeholder="Para que serve este modelo?">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <h4 class="text-xs font-black text-slate-800 uppercase italic mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-sm">info</span>
                            Dicas de Variáveis
                        </h4>
                        <p class="text-[10px] text-slate-500 font-medium leading-relaxed mb-4">
                            Você pode usar as seguintes tags no conteúdo para personalizar o e-mail automaticamente:
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-2 bg-white rounded-lg border border-slate-100 text-[9px]">
                                <code class="text-primary font-bold">@{nome}</code>
                                <p class="text-slate-400 mt-1 uppercase font-black tracking-tighter">Nome do Atleta</p>
                            </div>
                            <div class="p-2 bg-white rounded-lg border border-slate-100 text-[9px]">
                                <code class="text-primary font-bold">@{email}</code>
                                <p class="text-slate-400 mt-1 uppercase font-black tracking-tighter">E-mail do Atleta</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-50">

            <div>
                <div class="flex items-center justify-between mb-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Conteúdo do E-mail
                        (HTML/Markdown)</label>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Suporta formatação básica</span>
                </div>
                <textarea name="content" rows="15" required
                    class="w-full bg-slate-50 border-transparent rounded-2xl px-6 py-5 text-sm font-mono focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all"
                    placeholder="Escreva o conteúdo do e-mail aqui...">{{ old('content') }}</textarea>
                @error('content') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.emails.index') }}"
                    class="px-8 py-4 rounded-xl text-sm font-black uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">Cancelar</a>
                <button type="submit"
                    class="bg-primary text-white px-10 py-4 rounded-xl text-sm font-black uppercase tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20">
                    Criar Modelo
                </button>
            </div>
        </form>
    </div>
@endsection