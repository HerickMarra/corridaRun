<div id="media-gallery-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeMediaGallery()"></div>
    <div class="absolute inset-6 md:inset-12 bg-white rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-black uppercase italic tracking-tighter">Galeria de <span
                        class="text-primary">Mídia</span></h3>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Selecione, suba ou
                    gerencie seus arquivos</p>
            </div>
            <div class="flex items-center gap-4">
                <label
                    class="bg-primary text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:scale-105 transition-all shadow-lg shadow-primary/20">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined !text-sm">upload</span> Subir Fotos
                    </span>
                    <input type="file" multiple class="hidden" onchange="uploadMedia(this)">
                </label>
                <button onclick="closeMediaGallery()" class="text-slate-400 hover:text-red-500 transition-all">
                    <span class="material-symbols-outlined !text-3xl">close</span>
                </button>
            </div>
        </div>

        <!-- content -->
        <div class="flex-1 overflow-y-auto p-8" id="gallery-container">
            <div id="gallery-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <!-- Imagens via JS -->
            </div>

            <!-- Loading State -->
            <div id="gallery-loading" class="hidden py-12 flex flex-col items-center gap-3 text-slate-400">
                <div class="size-8 border-4 border-slate-100 border-t-primary rounded-full animate-spin"></div>
                <span class="text-[10px] font-bold uppercase tracking-widest">Carregando Galeria...</span>
            </div>

            <!-- Load More -->
            <div id="gallery-pagination" class="mt-8 flex justify-center hidden">
                <button onclick="loadMoreMedia()"
                    class="bg-slate-50 text-slate-500 px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
                    Carregar Mais
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .gallery-item.selected {
        outline: 4px solid var(--primary-color, #0d7ff2);
        outline-offset: 2px;
    }

    .gallery-item:hover .delete-btn {
        opacity: 1;
    }
</style>

<script>
    let currentGalleryPage = 1;
    let lastGalleryPage = 1;
    let galleryCallback = null;

    async function openMediaGallery(callback = null) {
        galleryCallback = callback;
        document.getElementById('media-gallery-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (currentGalleryPage === 1) {
            await loadMedia();
        }
    }

    function closeMediaGallery() {
        document.getElementById('media-gallery-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        galleryCallback = null;
    }

    async function loadMedia(append = false) {
        const loading = document.getElementById('gallery-loading');
        const grid = document.getElementById('gallery-grid');
        const pagination = document.getElementById('gallery-pagination');

        loading.classList.remove('hidden');
        if (!append) grid.innerHTML = '';

        try {
            const response = await fetch(`/admin/api/media?page=${currentGalleryPage}`);
            const data = await response.json();

            lastGalleryPage = data.last_page;

            data.data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'group relative aspect-square bg-slate-50 rounded-2xl overflow-hidden border border-slate-100 cursor-pointer hover:shadow-xl transition-all';
                div.onclick = () => selectMedia(item.url);

                div.innerHTML = `
                    <img src="${item.url}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/20 transition-all"></div>
                    <button onclick="deleteMedia(event, '${item.path}')" class="delete-btn absolute top-2 right-2 size-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 transition-all hover:scale-110 shadow-lg">
                        <span class="material-symbols-outlined !text-sm">delete</span>
                    </button>
                    <div class="absolute bottom-0 inset-x-0 p-2 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-all">
                        <p class="text-[8px] text-white font-bold truncate">${item.name}</p>
                    </div>
                `;
                grid.appendChild(div);
            });

            if (currentGalleryPage < lastGalleryPage) {
                pagination.classList.remove('hidden');
            } else {
                pagination.classList.add('hidden');
            }

        } catch (error) {
            console.error('Erro ao carregar mídia:', error);
            alert('Erro ao carregar galeria.');
        } finally {
            loading.classList.add('hidden');
        }
    }

    function loadMoreMedia() {
        if (currentGalleryPage < lastGalleryPage) {
            currentGalleryPage++;
            loadMedia(true);
        }
    }

    async function uploadMedia(input) {
        if (!input.files.length) return;

        const formData = new FormData();
        for (let file of input.files) {
            formData.append('files[]', file);
        }

        // Show loading
        document.getElementById('gallery-loading').classList.remove('hidden');

        try {
            const response = await fetch('/admin/api/media', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });

            if (response.ok) {
                currentGalleryPage = 1;
                loadMedia();
            } else {
                alert('Erro no upload. Verifique o tamanho e tipo do arquivo.');
            }
        } catch (error) {
            alert('Erro ao subir arquivos.');
        } finally {
            input.value = '';
        }
    }

    async function deleteMedia(event, path) {
        event.stopPropagation();
        if (!confirm('Tem certeza que deseja excluir esta imagem?')) return;

        try {
            const response = await fetch('/admin/api/media', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ path })
            });

            if (response.ok) {
                currentGalleryPage = 1;
                loadMedia();
            }
        } catch (error) {
            alert('Erro ao excluir arquivo.');
        }
    }

    function selectMedia(url) {
        if (galleryCallback) {
            galleryCallback(url);
            closeMediaGallery();
        }
    }
</script>