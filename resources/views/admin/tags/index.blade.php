@extends('layouts.admin')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-primary">Configurações</span>
                <span class="text-slate-300">/</span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tags de Evento</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter leading-none">
                Gestão de <span class="text-primary text-4xl leading-none">Tags</span>
            </h2>
            <p class="text-slate-500 text-sm font-medium mt-2">Crie e organize categorias para as suas corridas.</p>
        </div>

        <button onclick="openCreateTagModal()"
            class="bg-primary text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all flex items-center gap-3 shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-xl">add</span>
            Nova Tag
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($tags as $tag)
            <div
                class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-100 group hover:border-primary/20 transition-all flex flex-col justify-between h-48">
                <div class="flex justify-between items-start">
                    <div class="size-12 rounded-2xl flex items-center justify-center text-white shadow-lg"
                        style="background-color: {{ $tag->color_hex }}">
                        <span class="material-symbols-outlined text-2xl">label</span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openEditTagModal({{ json_encode($tag) }})"
                            class="size-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-primary hover:text-white transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </button>
                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST"
                            onsubmit="return confirm('Deseja realmente excluir esta tag?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="size-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                <div>
                    <h3
                        class="text-xl font-black text-slate-800 uppercase italic tracking-tighter group-hover:text-primary transition-colors">
                        {{ $tag->name }}</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $tag->slug }}</p>
                </div>
            </div>
        @endforeach

        @if($tags->isEmpty())
            <div class="col-span-full bg-white rounded-[40px] p-24 text-center border-2 border-dashed border-slate-100">
                <div class="size-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-4xl text-slate-200">sell</span>
                </div>
                <h3 class="text-xl font-black text-slate-300 uppercase italic tracking-tighter">Nenhuma Tag Criada</h3>
                <p class="text-slate-400 text-sm font-medium">As tags ajudam a filtrar corridas na home do site.</p>
            </div>
        @endif
    </div>

    <!-- Modals -->
    <div id="tag-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
        <div class="bg-white rounded-[40px] w-full max-w-sm shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 id="modal-title" class="text-xl font-black uppercase italic tracking-tighter text-slate-800">Nova Tag
                </h3>
                <button onclick="closeTagModal()" class="text-slate-400 hover:text-black transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="tag-form" method="POST" class="p-8 space-y-6">
                @csrf
                <div id="method-field"></div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome da Tag</label>
                    <input type="text" name="name" id="tag-name" required
                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                        placeholder="Ex: Trail Run">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cor de
                        Destaque</label>
                    <input type="color" name="color_hex" id="tag-color" value="#3490dc"
                        class="w-full h-14 bg-slate-50 border-transparent rounded-2xl px-2 py-1 focus:bg-white transition-all outline-none cursor-pointer">
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-5 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-xl italic">check_circle</span>
                    Confirmar Tag
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const modal = document.getElementById('tag-modal');
            const form = document.getElementById('tag-form');
            const title = document.getElementById('modal-title');
            const methodField = document.getElementById('method-field');
            const tagName = document.getElementById('tag-name');
            const tagColor = document.getElementById('tag-color');

            function openCreateTagModal() {
                title.innerText = 'Nova Tag';
                form.action = "{{ route('admin.tags.store') }}";
                methodField.innerHTML = '';
                tagName.value = '';
                tagColor.value = '#3490dc';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function openEditTagModal(tag) {
                title.innerText = 'Editar Tag';
                form.action = `/admin/tags/${tag.id}`;
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                tagName.value = tag.name;
                tagColor.value = tag.color_hex;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeTagModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        </script>
    @endpush
@endsection