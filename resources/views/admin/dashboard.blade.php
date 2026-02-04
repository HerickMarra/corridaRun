@extends('layouts.admin')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Stats Cards -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="size-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <span class="text-green-500 text-xs font-bold">+12%</span>
            </div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Vendas Totais</p>
            <h3 class="text-2xl font-black text-slate-800">R$ {{ number_format($totalSales, 2, ',', '.') }}</h3>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="size-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined">confirmation_number</span>
                </div>
                <span class="text-green-500 text-xs font-bold">+8%</span>
            </div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Inscrições Hoje</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $totalInscriptions }}</h3>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="size-12 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined">event</span>
                </div>
                <span class="text-slate-400 text-xs font-bold">Inabalável</span>
            </div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Eventos Ativos</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $activeEvents }}</h3>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="size-12 bg-green-50 text-green-500 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-green-500 text-xs font-bold">+24%</span>
            </div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Crescimento</p>
            <h3 class="text-2xl font-black text-slate-800">18.5%</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Sales -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h4 class="font-bold text-slate-800 uppercase tracking-tight">Vendas Recentes</h4>
                <button class="text-xs font-bold text-primary hover:underline">Ver todas</button>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Atleta
                            </th>
                            <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Evento
                            </th>
                            <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Valor</th>
                            <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentSales as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 bg-slate-100 rounded-full flex items-center justify-center text-xs font-bold text-slate-600">
                                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-semibold text-slate-700">{{ $order->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $order->items->first()->category->event->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">R$
                                    {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 {{ $order->status === \App\Enums\OrderStatus::Paid ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }} text-[10px] font-extrabold uppercase rounded-full tracking-wider">
                                        {{ $order->status->name }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm italic">Nenhuma venda
                                    recente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions / Right Sidebar item -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h4 class="font-bold text-slate-800 uppercase tracking-tight mb-6">Ações Rápidas</h4>
            <div class="space-y-4">
                <a href="{{ route('admin.corridas.create') }}"
                    class="w-full flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-primary/30 hover:bg-blue-50/30 transition-all text-left group">
                    <div
                        class="size-10 bg-blue-50 text-primary rounded-lg flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                        <span class="material-symbols-outlined">add</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Novo Evento</p>
                        <p class="text-[10px] text-slate-500 font-medium">Cadastrar corrida no sistema</p>
                    </div>
                </a>
                <button
                    class="w-full flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-primary/30 hover:bg-blue-50/30 transition-all text-left group">
                    <div
                        class="size-10 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                        <span class="material-symbols-outlined">mail</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Disparar E-mail</p>
                        <p class="text-[10px] text-slate-500 font-medium">Notificar inscritos de um evento</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
@endsection