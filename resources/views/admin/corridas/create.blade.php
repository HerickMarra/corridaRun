@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.corridas.index') }}"
            class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-primary transition-all flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Voltar para listagem
        </a>
        <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Cadastrar Nova <span
                class="text-primary">Corrida</span></h2>
        <p class="text-slate-500 text-sm font-medium">Preencha os detalhes para publicar uma nova prova no sistema.</p>
    </div>

    <form action="{{ route('admin.corridas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Informações Básicas -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                        Informações Básicas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome da
                                Corrida</label>
                            <input name="name" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="Ex: Maratona Internacional de SP" type="text" />
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Descrição</label>
                            <textarea name="description" required rows="4"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="Descreva os detalhes da prova..."></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Data da
                                Prova</label>
                            <input name="event_date" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                type="datetime-local" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Vagas
                                Totais</label>
                            <input name="max_participants" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="Ex: 5000" type="number" />
                        </div>
                    </div>
                </div>

                <!-- Categorias / Kits -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h3 class="text-lg font-black uppercase italic tracking-tight">Categorias & Kits</h3>
                        <button type="button" id="add-category"
                            class="text-primary font-black uppercase text-[10px] tracking-widest flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">add_circle</span> Adicionar Categoria
                        </button>
                    </div>

                    <div id="categories-container" class="space-y-6">
                        <div class="category-item p-6 rounded-2xl bg-slate-50 border border-slate-100 relative group">
                            <div
                                class="absolute -left-2 top-1/2 -translate-y-1/2 flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-all z-10">
                                <button type="button"
                                    class="move-up size-6 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 shadow-sm transition-all">
                                    <span class="material-symbols-outlined text-sm">arrow_upward</span>
                                </button>
                                <button type="button"
                                    class="move-down size-6 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 shadow-sm transition-all">
                                    <span class="material-symbols-outlined text-sm">arrow_downward</span>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Nome do
                                        Kit</label>
                                    <input name="categories[0][name]" required
                                        class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold"
                                        placeholder="Ex: Kit Premium" type="text" />
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400">Distância</label>
                                    <input name="categories[0][distance]" required
                                        class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold"
                                        placeholder="Ex: 21K" type="text" />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Preço
                                        (R$)</label>
                                    <input name="categories[0][price]" required
                                        class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold"
                                        placeholder="0.00" step="0.01" type="number" />
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400">Vagas</label>
                                    <input name="categories[0][max_participants]" required
                                        class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold"
                                        placeholder="500" type="number" />
                                </div>
                                <div class="space-y-1.5 lg:col-span-1">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400">Visibilidade</label>
                                    <select name="categories[0][is_public]"
                                        class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold">
                                        <option value="1">Público</option>
                                        <option value="0">Privado (Link Hash)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulário Personalizado -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h3 class="text-lg font-black uppercase italic tracking-tight">Formulário Personalizado</h3>
                        <button type="button" id="add-field"
                            class="text-primary font-black uppercase text-[10px] tracking-widest flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">add_circle</span> Adicionar Campo
                        </button>
                    </div>

                    <div id="fields-container" class="space-y-4">
                        <!-- Campos dinâmicos aqui -->
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-8">
                <!-- Localização -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                        Localização</h3>
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Local da
                                Largada</label>
                            <input name="location" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="Ex: Parque do Ibirapuera" type="text" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cidade</label>
                                <input name="city" required
                                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                    placeholder="São Paulo" type="text" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Estado
                                    (UF)</label>
                                <input name="state" required
                                    class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                    placeholder="SP" maxlength="2" type="text" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datas de Inscrição -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                        Período de Inscrição</h3>
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Início</label>
                            <input name="registration_start" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                type="datetime-local" />
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Término</label>
                            <input name="registration_end" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                type="datetime-local" />
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">Banner
                        da Prova</h3>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Selecionar
                            Imagem</label>
                        <input name="banner_image"
                            class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                            type="file" accept="image/*" />
                        <p class="text-[9px] text-slate-400 mt-2 font-medium">Recomendado: 1200x600px (JPEG, PNG). Max 2MB.
                        </p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-6 rounded-2xl text-sm font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Publicar Corrida
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        let categoryIndex = 1;
        document.getElementById('add-category').addEventListener('click', function () {
            const container = document.getElementById('categories-container');
            const html = `
                                        <div class="category-item p-6 rounded-2xl bg-slate-50 border border-slate-100 relative group animate-in fade-in slide-in-from-top-4 duration-500">
                                            <div class="absolute -left-2 top-1/2 -translate-y-1/2 flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-all z-10">
                                                <button type="button" class="move-up size-6 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 shadow-sm transition-all">
                                                    <span class="material-symbols-outlined text-sm">arrow_upward</span>
                                                </button>
                                                <button type="button" class="move-down size-6 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 shadow-sm transition-all">
                                                    <span class="material-symbols-outlined text-sm">arrow_downward</span>
                                                </button>
                                            </div>
                                            <button type="button" class="remove-category absolute -right-2 -top-2 size-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-lg">
                                                <span class="material-symbols-outlined text-xs">close</span>
                                            </button>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <div class="space-y-1.5">
                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Nome do Kit</label>
                                                    <input name="categories[${categoryIndex}][name]" required class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold" placeholder="Ex: Kit Premium" type="text"/>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Distância</label>
                                                    <input name="categories[${categoryIndex}][distance]" required class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold" placeholder="Ex: 21K" type="text"/>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Preço (R$)</label>
                                                    <input name="categories[${categoryIndex}][price]" required class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold" placeholder="0.00" step="0.01" type="number"/>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Vagas</label>
                                                    <input name="categories[${categoryIndex}][max_participants]" required class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold" placeholder="500" type="number"/>
                                                </div>
                                                <div class="space-y-1.5 lg:col-span-1">
                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Visibilidade</label>
                                                    <select name="categories[${categoryIndex}][is_public]" class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold">
                                                        <option value="1">Público</option>
                                                        <option value="0">Privado (Link Hash)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    `;
            container.insertAdjacentHTML('beforeend', html);
            categoryIndex++;
            reindexCategories();
        });

        function reindexCategories() {
            const items = document.querySelectorAll('.category-item');
            items.forEach((item, index) => {
                const inputs = item.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/categories\[\d+\]/, `categories[${index}]`));
                    }
                });
            });
        }

        document.addEventListener('click', function (e) {
            const item = e.target.closest('.category-item');
            if (!item) return;

            if (e.target.closest('.remove-category')) {
                item.remove();
                reindexCategories();
            }

            if (e.target.closest('.move-up')) {
                const prev = item.previousElementSibling;
                if (prev && prev.classList.contains('category-item')) {
                    item.parentNode.insertBefore(item, prev);
                    reindexCategories();
                }
            }

            if (e.target.closest('.move-down')) {
                const next = item.nextElementSibling;
                if (next && next.classList.contains('category-item')) {
                    item.parentNode.insertBefore(next, item);
                    reindexCategories();
                }
            }
        });

        let fieldIndex = 0;
        document.getElementById('add-field').addEventListener('click', function () {
            const container = document.getElementById('fields-container');
            const html = `
                            <div class="field-item p-4 rounded-xl bg-slate-50 border border-slate-100 relative group animate-in fade-in slide-in-from-top-2 duration-300">
                                <button type="button" class="remove-field absolute -right-2 -top-2 size-5 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-lg">
                                    <span class="material-symbols-outlined text-[10px]">close</span>
                                </button>
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-5 space-y-1">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Pergunta / Label</label>
                                        <input name="custom_fields[${fieldIndex}][label]" required class="w-full bg-white border-transparent rounded-lg px-3 py-2 text-xs font-bold" placeholder="Ex: Tamanho da Camiseta" type="text"/>
                                    </div>
                                    <div class="md:col-span-3 space-y-1">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Tipo</label>
                                        <select name="custom_fields[${fieldIndex}][type]" required class="type-selector w-full bg-white border-transparent rounded-lg px-3 py-2 text-xs font-bold">
                                            <option value="text">Texto Curto</option>
                                            <option value="number">Número</option>
                                            <option value="select">Seleção (Select)</option>
                                            <option value="textarea">Texto Longo</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-3 space-y-1">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Obrigatório?</label>
                                        <select name="custom_fields[${fieldIndex}][is_required]" class="w-full bg-white border-transparent rounded-lg px-3 py-2 text-xs font-bold">
                                            <option value="0">Não</option>
                                            <option value="1">Sim</option>
                                        </select>
                                    </div>
                                    <div class="options-container hidden md:col-span-12 space-y-1">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Opções (Separadas por vírgula)</label>
                                        <input name="custom_fields[${fieldIndex}][options]" class="w-full bg-white border-transparent rounded-lg px-3 py-2 text-xs font-bold" placeholder="Ex: P, M, G, GG"/>
                                    </div>
                                </div>
                            </div>
                        `;
            container.insertAdjacentHTML('beforeend', html);
            fieldIndex++;
        });

        function reindexFields() {
            const items = document.querySelectorAll('.field-item');
            items.forEach((item, index) => {
                const inputs = item.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/custom_fields\[\d+\]/, `custom_fields[${index}]`));
                    }
                });
            });
        }

        document.addEventListener('click', function (e) {
            const fieldItem = e.target.closest('.field-item');
            if (e.target.closest('.remove-field')) {
                fieldItem.remove();
                reindexFields();
            }

            const categoryItem = e.target.closest('.category-item');
            if (!categoryItem) return;
            // ... resto do listener ja existe ...
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('type-selector')) {
                const container = e.target.closest('.field-item').querySelector('.options-container');
                if (e.target.value === 'select') {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }
        });
    </script>
@endpush