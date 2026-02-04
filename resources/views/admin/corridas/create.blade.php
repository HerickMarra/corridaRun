@extends('layouts.admin')

@section('content')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
                                <div class="space-y-1.5 lg:col-span-4">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">O que
                                        está incluso (Separe por vírgula)</label>
                                    <textarea name="categories[0][items_included]"
                                        class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold"
                                        placeholder="Ex: Medalha, Camiseta, Chip, Hidratação" rows="2"></textarea>
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

                <!-- Cupons de Desconto -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h3 class="text-lg font-black uppercase italic tracking-tight">Cupons de Desconto</h3>
                        <button type="button" id="add-coupon"
                            class="text-primary font-black uppercase text-[10px] tracking-widest flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">add_circle</span> Novo Cupom
                        </button>
                    </div>

                    <div id="coupons-container" class="space-y-4">
                        <!-- Cupons aparecerão aqui -->
                    </div>
                </div>

                <!-- Regulamento -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                        Regulamento</h3>
                    <div id="regulation-editor" class="h-64"></div>
                    <input type="hidden" name="regulation" id="regulation-input">
                </div>

                <!-- Trajetos no Mapa (Google Maps) -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h3 class="text-lg font-black uppercase italic tracking-tight">Trajetos no Mapa</h3>
                        <button type="button" id="add-route"
                            class="text-primary font-black uppercase text-[10px] tracking-widest flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">map</span> Adicionar Percurso
                        </button>
                    </div>

                    <div id="routes-container" class="space-y-6">
                        <!-- Trajetos aparecerão aqui -->
                    </div>

                    <div id="map-container" class="mt-8 hidden">
                        <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
                            <div id="admin-map" class="w-full h-[500px]"></div>
                            <div
                                class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                                <p id="map-instruction"
                                    class="text-[10px] font-black uppercase tracking-widest text-primary">
                                    Clique no mapa para traçar o caminho
                                </p>
                            </div>
                            <div
                                class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 flex flex-wrap justify-center gap-2 w-full px-4">
                                <div
                                    class="flex gap-2 bg-white/90 backdrop-blur p-2 rounded-2xl shadow-lg border border-slate-200">
                                    <button type="button" id="tool-draw" title="Traçar Percurso"
                                        class="map-tool-btn size-10 rounded-xl flex items-center justify-center transition-all bg-primary text-white"
                                        data-tool="path">
                                        <span class="material-symbols-outlined text-xl">polyline</span>
                                    </button>
                                    <button type="button" id="tool-start" title="Marcar Largada"
                                        class="map-tool-btn size-10 rounded-xl flex items-center justify-center transition-all bg-slate-50 text-slate-400 hover:bg-slate-100"
                                        data-tool="start">
                                        <span class="material-symbols-outlined text-xl">location_on</span>
                                    </button>
                                    <button type="button" id="tool-end" title="Marcar Chegada"
                                        class="map-tool-btn size-10 rounded-xl flex items-center justify-center transition-all bg-slate-50 text-slate-400 hover:bg-slate-100"
                                        data-tool="end">
                                        <span class="material-symbols-outlined text-xl">sports_score</span>
                                    </button>
                                    <button type="button" id="tool-both" title="Largada e Chegada"
                                        class="map-tool-btn size-10 rounded-xl flex items-center justify-center transition-all bg-slate-50 text-slate-400 hover:bg-slate-100"
                                        data-tool="both">
                                        <span class="material-symbols-outlined text-xl">flag_circle</span>
                                    </button>
                                    <div class="w-px h-6 bg-slate-200 self-center mx-1"></div>
                                    <button type="button" id="clear-markers" title="Remover Marcadores"
                                        class="size-10 rounded-xl flex items-center justify-center transition-all bg-slate-50 text-red-400 hover:bg-red-50">
                                        <span class="material-symbols-outlined text-xl">location_off</span>
                                    </button>
                                    <button type="button" id="undo-draw" title="Desfazer"
                                        class="size-10 rounded-xl flex items-center justify-center transition-all bg-slate-50 text-slate-600 hover:bg-slate-100">
                                        <span class="material-symbols-outlined text-xl">undo</span>
                                    </button>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" id="finish-drawing"
                                        class="bg-black text-white px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg hover:scale-105 transition-all h-10 flex items-center">Finalizar</button>
                                    <button type="button" id="clear-route"
                                        class="bg-red-500 text-white px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg hover:scale-105 transition-all h-10 flex items-center">Limpar</button>
                                </div>
                            </div>
                        </div>
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
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        // Quill Configuration
        const quill = new Quill('#regulation-editor', {
            theme: 'snow',
            placeholder: 'Escreva ou cole aqui o regulamento completo da prova...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Update hidden input before submit
        quill.on('text-change', function () {
            document.getElementById('regulation-input').value = quill.root.innerHTML;
        });

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
                                                                                                <div class="space-y-1.5 lg:col-span-4">
                                                                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">O que está incluso (Separe por vírgula)</label>
                                                                                                    <textarea name="categories[${categoryIndex}][items_included]" class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold" placeholder="Ex: Medalha, Camiseta, Chip, Hidratação" rows="2"></textarea>
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

        let couponIndex = 0;
        document.getElementById('add-coupon').addEventListener('click', function () {
            const container = document.getElementById('coupons-container');
            const html = `
                                                                        <div class="coupon-item p-6 rounded-2xl bg-slate-50 border border-slate-100 relative group animate-in fade-in slide-in-from-top-4 duration-500">
                                                                            <button type="button" class="remove-coupon absolute -right-2 -top-2 size-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-lg">
                                                                                <span class="material-symbols-outlined text-xs">close</span>
                                                                            </button>
                                                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                                                                <div class="space-y-1.5">
                                                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Código</label>
                                                                                    <input name="coupons[${couponIndex}][code]" required class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold uppercase" placeholder="EX: PROMO10" type="text" />
                                                                                </div>
                                                                                <div class="space-y-1.5">
                                                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tipo</label>
                                                                                    <select name="coupons[${couponIndex}][type]" class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold">
                                                                                        <option value="percent">Porcentagem (%)</option>
                                                                                        <option value="fixed">Valor Fixo (R$)</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="space-y-1.5">
                                                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Valor</label>
                                                                                    <input name="coupons[${couponIndex}][value]" required class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold" placeholder="0.00" step="0.01" type="number" />
                                                                                </div>
                                                                                <div class="space-y-1.5">
                                                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Limite Uso</label>
                                                                                    <input name="coupons[${couponIndex}][usage_limit]" class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold" placeholder="Ilimitado" type="number" />
                                                                                </div>
                                                                                <div class="space-y-1.5">
                                                                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status</label>
                                                                                    <select name="coupons[${couponIndex}][is_active]" class="w-full bg-white border-transparent rounded-lg px-4 py-3 text-xs font-bold">
                                                                                        <option value="1">Ativo</option>
                                                                                        <option value="0">Inativo</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    `;
            container.insertAdjacentHTML('beforeend', html);
            couponIndex++;
        });

        function reindexCoupons() {
            const items = document.querySelectorAll('.coupon-item');
            items.forEach((item, index) => {
                const inputs = item.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/coupons\[\d+\]/, `coupons[${index}]`));
                    }
                });
            });
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-coupon')) {
                e.target.closest('.coupon-item').remove();
                reindexCoupons();
            }
        });

        // --- Google Maps Logic ---

        let map;
        let drawingPolyline;
        let activeRouteId = null;
        let activeTool = 'path'; // path, start, end, both
        let routeData = {}; // key: routeIndex, value: { polyline: Polyline, path: [{lat, lng}], markers: {}, markerObjects: {} }
        let routeIndex = 0;

        function initMap() {
            map = new google.maps.Map(document.getElementById("admin-map"), {
                center: { lat: -15.7942, lng: -47.8822 }, // Brasília default
                zoom: 13,
                mapId: 'RUNPACE_ADMIN_MAP',
                disableDefaultUI: false,
            });

            map.addListener("click", (e) => {
                if (activeRouteId === null) return;

                const latLng = { lat: e.latLng.lat(), lng: e.latLng.lng() };
                const currentRoute = routeData[activeRouteId];

                if (activeTool === 'path') {
                    currentRoute.path.push(latLng);
                    currentRoute.polyline.setPath(currentRoute.path);
                    document.getElementById(`route-path-${activeRouteId}`).value = JSON.stringify(currentRoute.path);
                } else {
                    placeMarker(activeTool, latLng);
                }
            });
        }

        function placeMarker(type, latLng) {
            const currentRoute = routeData[activeRouteId];

            // Remove existing marker object if any
            if (currentRoute.markerObjects[type]) {
                currentRoute.markerObjects[type].setMap(null);
            }

            // Create new marker with premium design
            let iconColor, symbol, isSymbol = false;
            if (type === 'start') { iconColor = "#22c55e"; symbol = "1"; }
            if (type === 'end') { iconColor = "#ef4444"; symbol = "2"; }
            if (type === 'both') { iconColor = "#0d59f2"; symbol = "flag_circle"; isSymbol = true; }

            const markerConfig = {
                position: latLng,
                map: map,
                animation: google.maps.Animation.DROP,
                label: {
                    text: symbol,
                    fontFamily: isSymbol ? "'Material Symbols Outlined'" : "Inter, sans-serif",
                    color: "#ffffff",
                    fontSize: isSymbol ? "18px" : "14px",
                    fontWeight: isSymbol ? "normal" : "900"
                },
                icon: {
                    path: "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z",
                    fillColor: iconColor,
                    fillOpacity: 1,
                    strokeWeight: 2,
                    strokeColor: "#ffffff",
                    scale: 1.8,
                    anchor: new google.maps.Point(12, 22),
                    labelOrigin: new google.maps.Point(12, 9)
                }
            };

            const marker = new google.maps.Marker(markerConfig);
            currentRoute.markerObjects[type] = marker;
            currentRoute.markers[type] = latLng;

            // Update hidden input
            document.getElementById(`route-markers-${activeRouteId}`).value = JSON.stringify(currentRoute.markers);
        }

        function reindexRoutes() {
            const items = document.querySelectorAll('.route-item');
            items.forEach((item, index) => {
                const inputs = item.querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/routes\[\d+\]/, `routes[${index}]`));
                    }
                });

                const btn = item.querySelector('.draw-route-btn');
                const colorInput = item.querySelector('.route-color-input');
                const pathInput = item.querySelector('.route-path-input');

                if (btn) btn.setAttribute('data-index', index);
                if (colorInput) colorInput.setAttribute('data-index', index);
                if (pathInput) pathInput.id = `route-path-${index}`;
            });
        }

        document.getElementById('add-route').addEventListener('click', function () {
            const container = document.getElementById('routes-container');
            const html = `
                                                                <div class="route-item p-6 rounded-2xl bg-slate-50 border border-slate-100 relative group animate-in slide-in-from-top-4 duration-500">
                                                                    <button type="button" class="remove-route absolute -right-2 -top-2 size-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-lg">
                                                                        <span class="material-symbols-outlined text-xs">close</span>
                                                                    </button>
                                                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                                                                        <div class="md:col-span-5 space-y-1.5">
                                                                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Nome do Trajeto</label>
                                                                            <input name="routes[${routeIndex}][name]" required class="w-full bg-white border-transparent rounded-xl px-4 py-3 text-xs font-bold" placeholder="Ex: Percurso 5KM" type="text"/>
                                                                        </div>
                                                                        <div class="md:col-span-3 space-y-1.5">
                                                                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Cor da Linha</label>
                                                                            <input name="routes[${routeIndex}][color]" value="#0d59f2" class="route-color-input w-full h-11 bg-white border-transparent rounded-xl px-1 py-1 cursor-pointer" type="color" data-index="${routeIndex}"/>
                                                                        </div>
                                                                        <div class="md:col-span-4">
                                                                            <button type="button" class="draw-route-btn w-full bg-slate-800 text-white py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-primary transition-all" data-index="${routeIndex}">
                                                                                <span class="material-symbols-outlined text-sm">edit_location_alt</span>
                                                                                Desenhar no Mapa
                                                                            </button>
                                                                        </div>
                                                                        <input type="hidden" name="routes[${routeIndex}][path]" id="route-path-${routeIndex}" class="route-path-input"/>
                                                                        <input type="hidden" name="routes[${routeIndex}][markers]" id="route-markers-${routeIndex}" class="route-markers-input"/>
                                                                    </div>
                                                                </div>
                                                            `;
            container.insertAdjacentHTML('beforeend', html);

            // Initialize polyline for this new route
            routeData[routeIndex] = {
                path: [],
                markers: { start: null, end: null, both: null },
                markerObjects: { start: null, end: null, both: null },
                polyline: new google.maps.Polyline({
                    strokeColor: "#0d59f2",
                    strokeOpacity: 0.8,
                    strokeWeight: 4,
                    map: map,
                    clickable: false
                })
            };

            routeIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.draw-route-btn')) {
                const btn = e.target.closest('.draw-route-btn');
                activeRouteId = btn.getAttribute('data-index');

                document.getElementById('map-container').classList.remove('hidden');
                document.getElementById('map-instruction').innerText = `DESENHANDO: ${btn.closest('.route-item').querySelector('input[type="text"]').value || 'Novo Percurso'}`;

                // Hide other items UI or focus map
                btn.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Set map color for active polyline
                const colorInput = btn.closest('.route-item').querySelector('.route-color-input');
                routeData[activeRouteId].polyline.setOptions({ strokeColor: colorInput.value });

                // Hide other polylines
                Object.keys(routeData).forEach(id => {
                    if (id != activeRouteId) {
                        routeData[id].polyline.setMap(null);
                    } else {
                        routeData[id].polyline.setMap(map);
                    }
                });
            }

            if (e.target.closest('#finish-drawing')) {
                activeRouteId = null;
                document.getElementById('map-container').classList.add('hidden');

                // Show all polylines and markers again
                Object.keys(routeData).forEach(id => {
                    routeData[id].polyline.setMap(map);
                    Object.keys(routeData[id].markerObjects).forEach(type => {
                        if (routeData[id].markerObjects[type]) {
                            routeData[id].markerObjects[type].setMap(map);
                        }
                    });
                });
            }

            if (e.target.closest('#undo-draw')) {
                if (activeRouteId !== null && routeData[activeRouteId].path.length > 0) {
                    routeData[activeRouteId].path.pop();
                    routeData[activeRouteId].polyline.setPath(routeData[activeRouteId].path);
                    document.getElementById(`route-path-${activeRouteId}`).value = JSON.stringify(routeData[activeRouteId].path);
                }
            }

            if (e.target.closest('#clear-markers')) {
                if (activeRouteId !== null) {
                    Object.keys(routeData[activeRouteId].markerObjects).forEach(type => {
                        if (routeData[activeRouteId].markerObjects[type]) {
                            routeData[activeRouteId].markerObjects[type].setMap(null);
                            routeData[activeRouteId].markerObjects[type] = null;
                            routeData[activeRouteId].markers[type] = null;
                        }
                    });
                    document.getElementById(`route-markers-${activeRouteId}`).value = "";
                }
            }

            if (e.target.closest('.map-tool-btn')) {
                const btn = e.target.closest('.map-tool-btn');
                activeTool = btn.getAttribute('data-tool');

                // Update UI
                document.querySelectorAll('.map-tool-btn').forEach(b => {
                    b.classList.remove('bg-primary', 'text-white');
                    b.classList.add('bg-slate-50', 'text-slate-400');
                });
                btn.classList.add('bg-primary', 'text-white');
                btn.classList.remove('bg-slate-50', 'text-slate-400');

                // Update instruction
                const labels = { 'path': 'Traçar Percurso', 'start': 'Marcar Largada', 'end': 'Marcar Chegada', 'both': 'Largada e Chegada' };
                document.getElementById('map-instruction').innerText = `${labels[activeTool].toUpperCase()}: clique no mapa`;
            }

            if (e.target.id === 'clear-route' || e.target.closest('#clear-route')) {
                if (activeRouteId !== null) {
                    routeData[activeRouteId].path = [];
                    routeData[activeRouteId].polyline.setPath([]);
                    document.getElementById(`route-path-${activeRouteId}`).value = "";

                    // Clear markers too
                    Object.keys(routeData[activeRouteId].markerObjects).forEach(type => {
                        if (routeData[activeRouteId].markerObjects[type]) {
                            routeData[activeRouteId].markerObjects[type].setMap(null);
                            routeData[activeRouteId].markerObjects[type] = null;
                            routeData[activeRouteId].markers[type] = null;
                        }
                    });
                    document.getElementById(`route-markers-${activeRouteId}`).value = "";
                }
            }

            if (e.target.closest('.remove-route')) {
                const item = e.target.closest('.route-item');
                const idx = item.querySelector('.draw-route-btn').getAttribute('data-index');
                if (routeData[idx]) {
                    routeData[idx].polyline.setMap(null);
                    Object.keys(routeData[idx].markerObjects).forEach(type => {
                        if (routeData[idx].markerObjects[type]) routeData[idx].markerObjects[type].setMap(null);
                    });
                    delete routeData[idx];
                }
                item.remove();
                reindexRoutes();
                routeIndex = document.querySelectorAll('.route-item').length;
            }
        });

        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('route-color-input')) {
                const idx = e.target.getAttribute('data-index');
                if (routeData[idx]) {
                    routeData[idx].polyline.setOptions({ strokeColor: e.target.value });
                }
            }
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap"
        async defer></script>
@endpush