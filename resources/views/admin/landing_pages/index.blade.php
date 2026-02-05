@extends('layouts.admin')

@section('title', 'Landing Pages')

@section('content')
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black uppercase italic tracking-tighter mb-2">Landing <span
                        class="text-primary">Pages</span></h1>
                <p class="text-slate-500 text-sm font-medium">Gerencie suas páginas de destino dinâmicas.</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openMediaGallery()" 
                    class="bg-slate-800 text-white px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg flex items-center gap-2">
                    <span class="material-symbols-outlined !text-xl">photo_library</span>
                    Galeria de Mídia
                </button>
                <a href="{{ route('admin.landing-pages.create') }}"
                    class="bg-primary text-white px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:scale-[1.02] transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span class="material-symbols-outlined !text-xl">add</span>
                    Nova Landing Page
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 card-shadow border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50">
                            <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Título</th>
                            <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Slug</th>
                            <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Template</th>
                            <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                            <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pages as $page)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-6">
                                    <p class="font-bold text-slate-900 group-hover:text-primary transition-colors">
                                        {{ $page->title }}</p>
                                </td>
                                <td class="py-6 font-mono text-xs text-slate-500">/{{ $page->slug }}</td>
                                <td class="py-6">
                                    <span
                                        class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                                        {{ $page->template->name }}
                                    </span>
                                </td>
                                <td class="py-6">
                                    @if($page->is_active)
                                        <span class="inline-flex items-center gap-1.5 text-green-600">
                                            <span class="size-1.5 rounded-full bg-green-600 animate-pulse"></span>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Ativo</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-slate-400">
                                            <span class="size-1.5 rounded-full bg-slate-400"></span>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Inativo</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('lp.show', $page->slug) }}" target="_blank"
                                            class="size-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-primary hover:bg-primary/5 transition-all">
                                            <span class="material-symbols-outlined !text-xl">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.landing-pages.edit', $page->id) }}"
                                            class="size-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-primary hover:bg-primary/5 transition-all">
                                            <span class="material-symbols-outlined !text-xl">edit</span>
                                        </a>
                                        <form action="{{ route('admin.landing-pages.destroy', $page->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta página?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="size-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all">
                                                <span class="material-symbols-outlined !text-xl">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <span class="material-symbols-outlined text-5xl text-slate-200">web_asset_off</span>
                                        <p class="text-slate-400 font-medium italic">Nenhuma landing page criada.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $pages->links() }}
            </div>
        </div>
    </div>
@endsection