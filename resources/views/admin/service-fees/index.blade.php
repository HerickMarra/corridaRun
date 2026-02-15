@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Taxas de <span
                    class="text-primary">Serviço</span></h2>
            <p class="text-slate-500 text-sm font-medium">Configure as taxas administrativas aplicadas globalmente no checkout.</p>
        </div>
        <a href="{{ route('admin.service-fees.create') }}" class="bg-primary text-white px-8 py-4 rounded-xl text-sm font-black uppercase tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Nova Taxa
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nome da Taxa</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tipo</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Valor</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($fees as $fee)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                        <span class="material-symbols-outlined">payments</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 leading-none">{{ $fee->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-black uppercase rounded tracking-tighter">
                                    {{ $fee->type === 'fixed' ? 'Valor Fixo' : 'Percentual' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-700">
                                    @if($fee->type === 'fixed')
                                        R$ {{ number_format($fee->value, 2, ',', '.') }}
                                    @else
                                        {{ $fee->value }}%
                                    @endif
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                @if($fee->is_active)
                                    <span class="w-fit px-2 py-0.5 bg-green-50 text-green-600 text-[8px] font-black uppercase rounded border border-green-100 tracking-tighter">Ativo</span>
                                @else
                                    <span class="w-fit px-2 py-0.5 bg-slate-50 text-slate-400 text-[8px] font-black uppercase rounded border border-slate-200 tracking-tighter">Inativo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 text-right">
                                    <a href="{{ route('admin.service-fees.edit', $fee->id) }}"
                                        class="px-3 py-2 rounded-lg bg-slate-50 text-slate-600 text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </a>
                                    
                                    <form action="{{ route('admin.service-fees.destroy', $fee->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta taxa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 rounded-lg bg-slate-50 text-red-500 text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-slate-200 text-6xl mb-4">payments</span>
                                <p class="text-slate-400 font-medium">Nenhuma taxa cadastrada.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
