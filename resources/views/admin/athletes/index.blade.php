@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Gestão de <span
                    class="text-primary">Atletas</span></h2>
            <p class="text-slate-500 text-sm font-medium">Visualize e gerencie todos os corredores cadastrados na
                plataforma.</p>
        </div>
    </div>

    <!-- Filtros e Busca -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8">
        <form action="{{ route('admin.athletes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-grow relative">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full bg-slate-50 border-transparent rounded-xl pl-12 pr-5 py-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all"
                    placeholder="Buscar atleta por nome, email ou CPF...">
            </div>
            <button type="submit"
                class="bg-secondary text-white px-8 py-4 rounded-xl text-sm font-bold uppercase tracking-widest hover:bg-black transition-all">
                Filtrar
            </button>
            @if(request('search'))
                <a href="{{ route('admin.athletes.index') }}"
                    class="flex items-center justify-center px-6 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all">
                    <span class="material-symbols-outlined">close</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Tabela de Atletas -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Atleta</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Contato</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">CPF / Nasc.
                        </th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">
                            Inscrições</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($athletes as $athlete)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="size-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-lg">
                                        {{ substr($athlete->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800 uppercase italic">{{ $athlete->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Membro desde
                                            {{ $athlete->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                        <span class="material-symbols-outlined text-sm text-primary">mail</span>
                                        {{ $athlete->email }}
                                    </div>
                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                        <span class="material-symbols-outlined text-sm text-primary">phone</span>
                                        {{ $athlete->phone ?? 'Não informado' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-xs font-bold text-slate-600 uppercase">
                                    <p>{{ $athlete->cpf ?? '---' }}</p>
                                    <p class="text-[10px] text-slate-400">
                                        {{ $athlete->birth_date ? $athlete->birth_date->format('d/m/Y') : '---' }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span
                                    class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $athlete->orders_count }} Provas
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="viewAthlete({{ $athlete->id }})"
                                        class="size-10 rounded-xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 transition-all group-hover:bg-white"
                                        title="Visualizar Perfil">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                            <a href="{{ route('admin.athletes.edit', $athlete->id) }}"
                                class="size-10 rounded-xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-secondary hover:border-secondary/30 transition-all group-hover:bg-white"
                                title="Editar Atleta">
                                <span class="material-symbols-outlined text-xl">edit_note</span>
                            </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <span class="material-symbols-outlined text-6xl text-slate-200">person_off</span>
                                    <p class="text-slate-500 font-medium">Nenhum atleta encontrado com os critérios de busca.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($athletes->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $athletes->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de Visualização -->
    <div id="athleteModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-6">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-800 uppercase italic">Detalhes do <span
                            class="text-primary">Atleta</span></h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto">
                    <!-- Info Pessoal -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Nome Completo</p>
                            <p id="modalName" class="font-bold text-slate-800 uppercase italic"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">E-mail</p>
                            <p id="modalEmail" class="font-bold text-slate-600"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">CPF</p>
                            <p id="modalCpf" class="font-bold text-slate-600"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Data de Nascimento
                            </p>
                            <p id="modalBirthDate" class="font-bold text-slate-600"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Telefone</p>
                            <p id="modalPhone" class="font-bold text-slate-600"></p>
                        </div>
                    </div>

                    <!-- Inscrições -->
                    <div>
                        <h4 class="text-sm font-black uppercase italic text-slate-800 mb-4 border-l-4 border-primary pl-3">
                            Inscrições Realizadas</h4>
                        <div class="bg-slate-50 rounded-2xl overflow-hidden border border-slate-100">
                            <table class="w-full text-left font-medium">
                                <thead class="bg-slate-100/50">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Evento</th>
                                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Categoria</th>
                                        <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Data em q se inscreveu</th>
                                    </tr>
                                </thead>
                                <tbody id="registrationsTable" class="divide-y divide-slate-100">
                                    <!-- Preenchido via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function viewAthlete(id) {
                fetch(`/admin/atletas/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const athlete = data.athlete;
                        const registrations = data.registrations;

                        document.getElementById('modalName').textContent = athlete.name;
                        document.getElementById('modalEmail').textContent = athlete.email;
                        document.getElementById('modalCpf').textContent = athlete.cpf || '---';
                        document.getElementById('modalBirthDate').textContent = athlete.birth_date ? new Date(athlete.birth_date)
                            .toLocaleDateString('pt-BR') : '---';
                        document.getElementById('modalPhone').textContent = athlete.phone || '---';

                        const tableBody = document.getElementById('registrationsTable');
                        tableBody.innerHTML = '';

                        if (registrations.length === 0) {
                            tableBody.innerHTML =
                                '<tr><td colspan="3" class="px-4 py-8 text-center text-xs text-slate-400 font-medium">Nenhuma inscrição encontrada</td></tr>';
                        } else {
                            registrations.forEach(reg => {
                                tableBody.innerHTML += `
                                    <tr>
                                        <td class="px-4 py-4">
                                            <p class="text-xs font-bold text-slate-700 uppercase italic">${reg.event_name}</p>
                                            ${reg.custom_responses.length > 0 ? `
                                                <div class="mt-2 space-y-1 bg-white p-2 rounded-lg border border-slate-100">
                                                    ${reg.custom_responses.map(resp => `
                                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter">
                                                            <span class="text-primary">${resp.label}:</span> ${resp.value}
                                                        </p>
                                                    `).join('')}
                                                </div>
                                            ` : ''}
                                        </td>
                                        <td class="px-4 py-4 text-xs font-bold text-slate-500 uppercase">${reg.category_name}</td>
                                        <td class="px-4 py-4 text-xs text-slate-400 font-medium">${reg.date}</td>
                                    </tr>
                                `;
                            });
                        }

                        document.getElementById('athleteModal').classList.remove('hidden');
                    });
            }

            function closeModal() {
                document.getElementById('athleteModal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection