@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Modelos de <span
                    class="text-primary">E-mail</span></h2>
            <p class="text-slate-500 text-sm font-medium">Gerencie o conteúdo e o visual das comunicações do sistema.</p>
        </div>
        <a href="{{ route('admin.emails.create') }}" class="bg-primary text-white px-8 py-4 rounded-xl text-sm font-black uppercase tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Novo Modelo
        </a>
    </div>

    @if(session('success'))
        <div
            class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div
            class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-xl">error</span>
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identificador / Nome</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Assunto</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tipo / Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($templates as $template)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                        <span class="material-symbols-outlined">mail</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 leading-none mb-1">{{ $template->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $template->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-slate-700 leading-relaxed">{{ $template->subject }}</p>
                                @if($template->description)
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ Str::limit($template->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5">
                                    @if($template->is_system)
                                        <span class="w-fit px-2 py-0.5 bg-blue-100 text-blue-600 text-[8px] font-black uppercase rounded border border-blue-200 tracking-tighter">Sistema</span>
                                    @else
                                        <span class="w-fit px-2 py-0.5 bg-purple-100 text-purple-600 text-[8px] font-black uppercase rounded border border-purple-200 tracking-tighter">Marketing</span>
                                    @endif

                                    @if($template->is_active)
                                        <span class="w-fit px-2 py-0.5 bg-green-50 text-green-600 text-[8px] font-black uppercase rounded border border-green-100 tracking-tighter">Ativo</span>
                                    @else
                                        <span class="w-fit px-2 py-0.5 bg-slate-50 text-slate-400 text-[8px] font-black uppercase rounded border border-slate-200 tracking-tighter">Inativo</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 text-right">
                                    <a href="{{ route('admin.emails.edit', $template->id) }}"
                                        class="px-3 py-2 rounded-lg bg-slate-50 text-slate-600 text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all flex items-center gap-2"
                                        title="Editar conteúdo">
                                        <span class="material-symbols-outlined text-sm">edit_note</span>
                                    </a>
                                    
                                    @if(!$template->is_system)
                                        <form action="{{ route('admin.emails.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este modelo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 rounded-lg bg-slate-50 text-red-500 text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all flex items-center gap-2">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="px-3 py-2 rounded-lg bg-slate-50 text-slate-200 text-[10px] cursor-not-allowed flex items-center gap-2" title="Modelos de sistema não podem ser excluídos">
                                            <span class="material-symbols-outlined text-sm">lock</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-slate-200 text-6xl mb-4">mail_lock</span>
                                <p class="text-slate-400 font-medium">Nenhum modelo de e-mail cadastrado.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
