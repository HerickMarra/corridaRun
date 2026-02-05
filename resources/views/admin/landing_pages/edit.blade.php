@extends('layouts.admin')

@section('title', 'Editar Landing Page - ' . $landingPage->title)

@section('content')
    <div class="space-y-8" id="lp-editor-app">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.landing-pages.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition-all flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined !text-sm">arrow_back</span>
                    Voltar para lista
                </a>
                <h1 class="text-3xl font-black uppercase italic tracking-tighter mb-2">Editar <span class="text-primary">Landing Page</span></h1>
                <p class="text-slate-500 text-sm font-medium">Personalize o conteúdo do tema <strong>{{ $landingPage->template->name }}</strong>.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('lp.show', $landingPage->slug) }}" target="_blank" class="px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined !text-xl">visibility</span>
                    Visualizar
                </a>
                <button type="submit" form="lp-edit-form" class="bg-primary text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:scale-[1.02] transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span class="material-symbols-outlined !text-xl">save</span>
                    Salvar Alterações
                </button>
            </div>
        </div>

        <form id="lp-edit-form" action="{{ route('admin.landing-pages.update', $landingPage->id) }}" method="POST" enctype="multipart/form-data" class="grid lg:grid-cols-3 gap-8">
            @csrf @method('PUT')
            
            <div class="lg:col-span-2 space-y-8">
                @foreach($landingPage->template->config_schema as $sectionKey => $fields)
                    <section class="bg-white rounded-[2.5rem] p-8 card-shadow border border-slate-50">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="size-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">segment</span>
                            </div>
                            <h2 class="text-lg font-black uppercase italic tracking-tight">{{ ucfirst($sectionKey) }}</h2>
                        </div>

                        <div class="space-y-6">
                            @foreach($fields as $field)
                                @php
                                    $value = $landingPage->content[$sectionKey][$field['key']] ?? '';
                                    $inputName = "content[{$sectionKey}][{$field['key']}]";
                                @endphp

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ $field['label'] }}</label>
                                    
                                    @if($field['type'] === 'text')
                                        <input name="{{ $inputName }}" type="text" value="{{ $value }}"
                                            class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all">
                                    @elseif($field['type'] === 'textarea')
                                        <textarea name="{{ $inputName }}" rows="3"
                                            class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all">{{ $value }}</textarea>
                                    @elseif($field['type'] === 'image')
                                        <div class="space-y-3">
                                            @php $previewId = "preview-{$sectionKey}-{$field['key']}"; $statusId = "status-{$sectionKey}-{$field['key']}"; @endphp
                                            <div class="relative w-32 h-20 rounded-xl overflow-hidden border border-slate-100 group {{ $value ? '' : 'hidden' }}">
                                                <img src="{{ asset($value) }}" id="{{ $previewId }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <input name="{{ $inputName }}" type="text" value="{{ $value }}" id="input-{{ $previewId }}"
                                                    class="flex-1 bg-slate-50 border-transparent rounded-2xl px-5 py-3 text-[10px] font-bold text-slate-400 truncate focus:bg-white transition-all">
                                                <button type="button" onclick="openMediaGallery(url => updateImageField('{{ $previewId }}', url))" 
                                                    class="bg-slate-800 text-white px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap hover:bg-black transition-all">
                                                    Galeria
                                                </button>
                                            </div>
                                        </div>
                                    @elseif($field['type'] === 'array')
                                        <div class="space-y-4 pt-2" id="repeater-{{ $sectionKey }}-{{ $field['key'] }}">
                                            <div class="items-container space-y-4">
                                                @foreach(($value ?: []) as $index => $item)
                                                    <div class="repeater-item bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100 relative group">
                                                        <button type="button" onclick="this.parentElement.remove()" class="absolute top-4 right-4 size-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                                            <span class="material-symbols-outlined !text-sm">close</span>
                                                        </button>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            @foreach($field['item_schema'] as $subField)
                                                                <div class="space-y-1">
                                                                    <label class="text-[9px] font-black uppercase text-slate-400">{{ $subField['label'] }}</label>
                                                                    @if($subField['type'] === 'boolean')
                                                                        <select name="content[{{ $sectionKey }}][{{ $field['key'] }}][{{ $index }}][{{ $subField['key'] }}]" class="w-full bg-white border-transparent rounded-xl px-4 py-2 text-xs font-bold">
                                                                            <option value="0" {{ !($item[$subField['key']] ?? false) ? 'selected' : '' }}>Não</option>
                                                                            <option value="1" {{ ($item[$subField['key']] ?? false) ? 'selected' : '' }}>Sim</option>
                                                                        </select>
                                                                    @elseif($subField['type'] === 'image')
                                                                    @php
                                                                        $subFieldId = "item-{$sectionKey}-{$field['key']}-{$index}-{$subField['key']}";
                                                                    @endphp
                                                                    <div class="space-y-2">
                                                                        <div class="{{ ($item[$subField['key']] ?? '') ? '' : 'hidden' }}">
                                                                            <img src="{{ asset($item[$subField['key']] ?? '') }}" id="preview-{{ $subFieldId }}" class="h-8 rounded-lg">
                                                                        </div>
                                                                        <div class="flex items-center gap-2">
                                                                            <input name="content[{{ $sectionKey }}][{{ $field['key'] }}][{{ $index }}][{{ $subField['key'] }}]" 
                                                                                   type="text" value="{{ $item[$subField['key']] ?? '' }}" id="input-preview-{{ $subFieldId }}"
                                                                                   class="flex-1 bg-white border-transparent rounded-xl px-4 py-2 text-[10px] font-bold truncate">
                                                                            <button type="button" onclick="openMediaGallery(url => updateImageField('preview-{{ $subFieldId }}', url))"
                                                                                class="bg-slate-800 text-white px-3 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest whitespace-nowrap">
                                                                                Galeria
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    @else
                                                                        <input name="content[{{ $sectionKey }}][{{ $field['key'] }}][{{ $index }}][{{ $subField['key'] }}]" 
                                                                               type="text" value="{{ $item[$subField['key']] ?? '' }}"
                                                                               class="w-full bg-white border-transparent rounded-xl px-4 py-2 text-xs font-bold">
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" onclick="addItem('{{ $sectionKey }}', '{{ $field['key'] }}', {{ json_encode($field['item_schema']) }})" 
                                                class="flex items-center gap-2 text-primary font-black uppercase text-[10px] tracking-widest hover:gap-3 transition-all px-4 py-2 bg-primary/5 rounded-xl w-fit">
                                                <span class="material-symbols-outlined !text-sm">add</span> Adicionar Item
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <div class="space-y-8">
                <section class="bg-white rounded-[2.5rem] p-8 card-shadow border border-slate-50 space-y-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="size-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">settings</span>
                        </div>
                        <h2 class="text-lg font-black uppercase italic tracking-tight">Atributos</h2>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Título SEO</label>
                        <input name="title" type="text" value="{{ $landingPage->title }}" required
                            class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Slug (URL)</label>
                        <input name="slug" type="text" value="{{ $landingPage->slug }}" required
                            class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all">
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer group pt-2">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" {{ $landingPage->is_active ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </div>
                        <span class="text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-primary transition-all">Página Online</span>
                    </label>
                </section>
                
                <div class="bg-primary/5 p-8 rounded-[2.5rem] border border-primary/10">
                    <p class="text-[10px] font-black uppercase tracking-widest text-primary mb-2">Dica de Especialista</p>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium italic">
                        "Lembre-se de usar URLs de imagens que carreguem rápido (WebP ou URLs de CDNs). Isso ajuda na pontuação do Google para a sua Landing Page."
                    </p>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateImageField(previewId, url) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = url;
                preview.parentElement.classList.remove('hidden');
            }
            const input = document.getElementById('input-' + previewId);
            if (input) {
                input.value = url;
            }
        }

        function addItem(section, field, schema) {
            const container = document.querySelector(`#repeater-${section}-${field} .items-container`);
            const index = container.children.length;
            const itemId = `item-${section}-${field}-${index}`;
            
            const div = document.createElement('div');
            div.className = 'repeater-item bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100 relative group animate-in slide-in-from-top-4 duration-300';
            
            let fieldsHtml = '';
            schema.forEach(subField => {
                let inputHtml = "";
                const subFieldId = `item-${section}-${field}-${index}-${subField.key}`;
                const previewId = `preview-${subFieldId}`;
                
                if (subField.type === 'boolean') {
                    inputHtml = `
                        <select name="content[${section}][${field}][${index}][${subField.key}]" class="w-full bg-white border-transparent rounded-xl px-4 py-2 text-xs font-bold">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    `;
                } else if (subField.type === 'image') {
                    inputHtml = `
                        <div class="space-y-2">
                            <div class="hidden">
                                <img src="" id="${previewId}" class="h-8 rounded-lg">
                            </div>
                            <div class="flex items-center gap-2">
                                <input name="content[${section}][${field}][${index}][${subField.key}]" type="text" value="" id="input-${previewId}"
                                    class="flex-1 bg-white border-transparent rounded-xl px-4 py-2 text-[10px] font-bold truncate">
                                <button type="button" onclick="openMediaGallery(url => updateImageField('${previewId}', url))"
                                    class="bg-slate-800 text-white px-3 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest whitespace-nowrap">
                                    Galeria
                                </button>
                            </div>
                        </div>
                    `;
                } else {
                    inputHtml = `
                        <input name="content[${section}][${field}][${index}][${subField.key}]" 
                               type="text" value=""
                               class="w-full bg-white border-transparent rounded-xl px-4 py-2 text-xs font-bold">
                    `;
                }

                fieldsHtml += `
                    <div class="space-y-1">
                        <label class="text-[9px] font-black uppercase text-slate-400">${subField.label}</label>
                        ${inputHtml}
                    </div>
                `;
            });

            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-4 right-4 size-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                    <span class="material-symbols-outlined !text-sm">close</span>
                </button>
                <div class="grid grid-cols-2 gap-4">
                    ${fieldsHtml}
                </div>
            `;
            
            container.appendChild(div);
        }
    </script>
@endsection
