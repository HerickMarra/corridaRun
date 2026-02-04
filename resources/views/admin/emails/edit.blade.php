@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('admin.emails.index') }}"
                class="size-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Editar <span
                        class="text-primary">E-mail</span></h2>
                <p class="text-slate-500 text-sm font-medium">Identificador: <span
                        class="font-bold text-slate-700 uppercase">{{ $template->slug }}</span></p>
            </div>
        </div>

        <form action="{{ route('admin.emails.update', $template->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Configurações do Template -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                            Conteúdo do E-mail</h3>

                        <div class="space-y-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Assunto
                                    do E-mail</label>
                                <input type="text" name="subject" value="{{ old('subject', $template->subject) }}" required
                                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                    placeholder="Ex: Confirmação de Inscrição - @{prova}">
                                @error('subject') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">
                                {{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Corpo do
                                    E-mail (Markdown/HTML)</label>
                                <textarea name="content" required rows="15"
                                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none font-mono"
                                    placeholder="Olá @{nome}, sua inscrição foi confirmada...">{{ old('content', $template->content) }}</textarea>
                                @error('content') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">
                                {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-black uppercase italic tracking-tight mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">variable</span>
                            Variáveis Disponíveis
                        </h3>
                        <p class="text-slate-500 text-xs mb-6 font-medium leading-relaxed">Utilize as tags abaixo para
                            personalizar o e-mail automaticamente. Elas serão substituídas pelos dados reais no momento do
                            envio.</p>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <div
                                class="p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:border-primary/30 transition-all">
                                <p
                                    class="text-[10px] font-black text-primary uppercase tracking-tighter mb-0.5 group-hover:scale-105 transition-transform origin-left">
                                    @{nome}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Nome do Atleta</p>
                            </div>
                            <div
                                class="p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:border-primary/30 transition-all">
                                <p
                                    class="text-[10px] font-black text-primary uppercase tracking-tighter mb-0.5 group-hover:scale-105 transition-transform origin-left">
                                    @{prova}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Nome da Prova</p>
                            </div>
                            <div
                                class="p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:border-primary/30 transition-all">
                                <p
                                    class="text-[10px] font-black text-primary uppercase tracking-tighter mb-0.5 group-hover:scale-105 transition-transform origin-left">
                                    @{data}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Data do Evento</p>
                            </div>
                            <div
                                class="p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:border-primary/30 transition-all">
                                <p
                                    class="text-[10px] font-black text-primary uppercase tracking-tighter mb-0.5 group-hover:scale-105 transition-transform origin-left">
                                    @{inscricao}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Número do Pedido
                                </p>
                            </div>
                            <div
                                class="p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:border-primary/30 transition-all">
                                <p
                                    class="text-[10px] font-black text-primary uppercase tracking-tighter mb-0.5 group-hover:scale-105 transition-transform origin-left">
                                    @{link_evento}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">URL da Prova</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalhes e Status -->
                <div class="space-y-8">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                            Informações</h3>

                        <div class="space-y-6">
                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Descrição
                                    Interna</label>
                                <textarea name="description" rows="3"
                                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                    placeholder="Explique para que serve este modelo...">{{ old('description', $template->description) }}</textarea>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl group">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-widest text-slate-700">Template Ativo
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-medium">Permite disparos usando este modelo
                                    </p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <button type="submit"
                            class="w-full bg-primary text-white py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">save</span>
                            Salvar Alterações
                        </button>
                        <a href="{{ route('admin.emails.index') }}"
                            class="w-full bg-white border border-slate-200 text-slate-600 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all text-center">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection