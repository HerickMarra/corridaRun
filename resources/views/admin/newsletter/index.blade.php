@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Inscritos na <span class="text-primary">Newsletter</span></h2>
            <p class="text-slate-500 text-sm font-medium">Gerencie o público que deseja receber avisos e promoções.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 flex items-center gap-2">
                <span class="text-primary font-black text-lg">{{ $totalActive }}</span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Inscritos Ativos</span>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8">
        <form action="{{ route('admin.newsletter.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-grow space-y-1.5 w-full">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Buscar por e-mail</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input name="search" value="{{ request('search') }}"
                        class="w-full bg-slate-50 border-transparent rounded-xl pl-11 pr-5 py-3 text-sm font-bold focus:bg-white transition-all"
                        placeholder="Digite o e-mail..." type="text" />
                </div>
            </div>
            <div class="w-full md:w-48 space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Status</label>
                <select name="status" class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-3 text-sm font-bold focus:bg-white transition-all">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                    <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Cancelados</option>
                </select>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.newsletter.index') }}" class="bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Limpar</a>
                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition-all">Filtrar</button>
            </div>
        </form>
    </div>

    {{-- Subscribers Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">E-mail</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Data de Inscrição</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($subscribers as $subscriber)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-slate-800">{{ $subscriber->email }}</p>
                            </td>
                            <td class="px-8 py-5">
                                @if($subscriber->status === 'active')
                                    <span class="bg-green-50 text-green-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter">Ativo</span>
                                @else
                                    <span class="bg-red-50 text-red-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter">Cancelado</span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-slate-600 tracking-tight">{{ $subscriber->subscribed_at->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este inscrito?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Remover">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <span class="material-symbols-outlined text-slate-200 text-6xl mb-4">mail</span>
                                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Nenhum inscrito encontrado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>
@endsection
