@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.marketing.index') }}"
            class="size-12 rounded-2xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Nova <span
                    class="text-primary">Campanha</span></h2>
            <p class="text-slate-500 text-sm font-medium">Configure seu disparo em massa para os atletas.</p>
        </div>
    </div>

    <form action="{{ route('admin.marketing.store') }}" method="POST" id="campaignForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Configuração -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-6">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Nome da
                            Campanha (Interno)</label>
                        <input type="text" name="name" required
                            class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all"
                            placeholder="Ex: Informativo Corrida de Verão">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Assunto do
                            E-mail</label>
                        <input type="text" name="subject" required
                            class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all"
                            placeholder="O que o atleta verá no assunto">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Modelo de
                            E-mail</label>
                        <select name="template_id" required
                            class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="">Selecione um template...</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black uppercase italic text-slate-800 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">groups</span>
                        Público-Alvo
                    </h3>

                    <div class="space-y-6">
                        <label
                            class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border-2 border-transparent cursor-pointer hover:border-primary/20 transition-all group has-[:checked]:bg-primary/5 has-[:checked]:border-primary">
                            <input type="checkbox" name="target_all" value="1" id="targetAll"
                                class="size-5 rounded-lg border-slate-200 text-primary focus:ring-primary">
                            <div>
                                <p class="text-sm font-black text-slate-800 uppercase italic">Enviar para TODOS os atletas
                                </p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Toda a base de
                                    clientes cadastrados</p>
                            </div>
                        </label>

                        <div id="eventSelection">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 block">Ou
                                selecione as corridas específicas:</label>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($events as $event)
                                    <label
                                        class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer has-[:checked]:bg-primary/5 has-[:checked]:border-primary/30">
                                        <input type="checkbox" name="event_ids[]" value="{{ $event->id }}"
                                            class="size-4 rounded border-slate-200 text-primary focus:ring-primary event-checkbox">
                                        <div>
                                            <p class="text-xs font-bold text-slate-700 uppercase italic">{{ $event->name }}</p>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">
                                                {{ $event->event_date->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumo e Envio -->
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 sticky top-8">
                    <h3 class="text-sm font-black uppercase italic text-slate-800 mb-6">Resumo do Envio</h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center py-3 border-b border-slate-50">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total de
                                Destinatários</span>
                            <span id="recipientCount" class="text-xl font-black text-primary italic">0</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-slate-50">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Método</span>
                            <span class="text-[10px] font-black text-slate-600 uppercase">Fila Assíncrona (Job)</span>
                        </div>
                        <div class="space-y-1.5 pt-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Agendar para
                                (Opcional)</label>
                            <input type="datetime-local" name="scheduled_at" id="scheduledAt"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-4 py-3 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all">
                            <p class="text-[9px] text-slate-400 ml-1 font-medium">Deixe vazio para enviar agora.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 mb-8">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-amber-500">warning</span>
                            <p class="text-[10px] font-bold text-amber-700 leading-relaxed uppercase tracking-tighter">
                                Atenção: Esta ação é irreversível. Certifique-se de que o template e o assunto estão
                                corretos antes de disparar.
                            </p>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit"
                        class="w-full bg-primary text-white py-5 rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-secondary transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined" id="btnIcon">send</span>
                        <span id="btnText">Disparar Agora</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            const targetAll = document.getElementById('targetAll');
            const eventCheckboxes = document.querySelectorAll('.event-checkbox');
            const recipientCountSpan = document.getElementById('recipientCount');
            const eventSelectionDiv = document.getElementById('eventSelection');

            function updateCount() {
                const formData = new FormData(document.getElementById('campaignForm'));
                const params = new URLSearchParams();

                for (const pair of formData.entries()) {
                    if (pair[0].endsWith('[]')) {
                        params.append(pair[0], pair[1]);
                    } else {
                        params.set(pair[0], pair[1]);
                    }
                }

                fetch(`{{ route('admin.marketing.recipient-count') }}?${params.toString()}`)
                    .then(res => res.json())
                    .then(data => {
                        recipientCountSpan.textContent = data.count;
                    });
            }

            targetAll.addEventListener('change', function () {
                if (this.checked) {
                    eventSelectionDiv.classList.add('opacity-50', 'pointer-events-none');
                    eventCheckboxes.forEach(cb => cb.checked = false);
                } else {
                    eventSelectionDiv.classList.remove('opacity-50', 'pointer-events-none');
                }
                updateCount();
            });

            eventCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateCount);
            });

            document.getElementById('scheduledAt').addEventListener('change', function () {
                const btnText = document.getElementById('btnText');
                const btnIcon = document.getElementById('btnIcon');
                if (this.value) {
                    btnText.textContent = 'Agendar Campanha';
                    btnIcon.textContent = 'schedule';
                } else {
                    btnText.textContent = 'Disparar Agora';
                    btnIcon.textContent = 'send';
                }
            });

            document.getElementById('campaignForm').addEventListener('submit', function () {
                const btn = document.getElementById('btnSubmit');
                btn.disabled = true;
                btn.innerHTML = '<span class="animate-spin material-symbols-outlined">sync</span> Processando...';
            });

            // Inicializar
            updateCount();
        </script>
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f8fafc;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #e2e8f0;
                border-radius: 10px;
            }
        </style>
    @endpush
@endsection