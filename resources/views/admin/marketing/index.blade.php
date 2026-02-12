@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Mail <span class="text-primary">Marketing</span></h2>
            <p class="text-slate-500 text-sm font-medium">Gerencie e acompanhe seus disparos de e-mail em massa.</p>
        </div>
        <a href="{{ route('admin.marketing.create') }}" class="bg-primary text-white px-8 py-4 rounded-xl text-sm font-black uppercase tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Nova Campanha
        </a>
    </div>

    <!-- Tabela de Campanhas -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Campanha</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Assunto</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Destinatários</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Data de Envio</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($campaigns as $campaign)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-800 uppercase italic">{{ $campaign->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Template: {{ $campaign->template->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-8 py-6 text-sm font-bold text-slate-600 italic">
                                {{ $campaign->subject }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase">
                                    {{ $campaign->processed_recipients }} / {{ $campaign->total_recipients }} pessoas
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @if($campaign->status === 'sent')
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-lg text-[10px] font-black uppercase">Enviado</span>
                                @elseif($campaign->status === 'sending')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-[10px] font-black uppercase animate-pulse">Enviando...</span>
                                @elseif($campaign->status === 'draft' && $campaign->scheduled_at)
                                    <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-lg text-[10px] font-black uppercase">Agendado</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-400 rounded-lg text-[10px] font-black uppercase">{{ $campaign->status }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-xs font-bold text-slate-400">
                                @if($campaign->sent_at)
                                    {{ $campaign->sent_at->format('d/m/Y H:i') }}
                                @elseif($campaign->scheduled_at)
                                    <span class="text-amber-500">{{ $campaign->scheduled_at->format('d/m/Y H:i') }} (Agendado)</span>
                                @else
                                    ---
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <span class="material-symbols-outlined text-6xl text-slate-200">mail_outline</span>
                                    <p class="text-slate-500 font-medium">Nenhuma campanha enviada até o momento.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($campaigns->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>
@endsection
