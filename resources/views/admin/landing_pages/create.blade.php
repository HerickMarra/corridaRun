@extends('layouts.admin')

@section('title', 'Nova Landing Page')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8">
        <div>
            <a href="{{ route('admin.landing-pages.index') }}"
                class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition-all flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined !text-sm">arrow_back</span>
                Voltar para lista
            </a>
            <h1 class="text-3xl font-black uppercase italic tracking-tighter mb-2">Criar <span class="text-primary">Landing
                    Page</span></h1>
            <p class="text-slate-500 text-sm font-medium">Defina o nome, slug e o tema base.</p>
        </div>

        <form action="{{ route('admin.landing-pages.store') }}" method="POST"
            class="bg-white rounded-[2.5rem] p-8 card-shadow border border-slate-50 space-y-6">
            @csrf

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Título da Página</label>
                <input name="title" type="text" required placeholder="Ex: Maratona do Sol 2024"
                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all">
                @error('title') <p class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Slug (URL)</label>
                <div class="flex items-center">
                    <span
                        class="bg-slate-100 px-5 py-4 rounded-l-2xl text-sm font-bold text-slate-400 border-r border-slate-200">{{ Request::root() }}/</span>
                    <input name="slug" type="text" placeholder="maratona-sol-2024"
                        class="flex-1 bg-slate-50 border-transparent rounded-r-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all">
                </div>
                <p class="text-[10px] text-slate-400 mt-1 ml-1 italic">Deixe em branco para gerar automaticamente do título.
                </p>
                @error('slug') <p class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Modelo
                    (Template)</label>
                <select name="landing_page_template_id" required
                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all">
                    <option value="">Selecione um tema</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
                @error('landing_page_template_id') <p class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">
                {{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative">
                    <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                    </div>
                </div>
                <span
                    class="text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-primary transition-all">Página
                    Ativa</span>
            </label>

            <button type="submit"
                class="w-full bg-primary text-white py-6 rounded-2xl text-sm font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                Criar e Continuar
            </button>
        </form>
    </div>
@endsection