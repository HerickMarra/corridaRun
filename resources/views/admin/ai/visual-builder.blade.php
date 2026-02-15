@extends('layouts.admin')

@section('content')
    <div class="mb-8 items-center flex justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Construtor Visual de <span
                    class="text-primary">Inteligência</span></h2>
            <p class="text-slate-500 text-sm font-medium">Gerencie a personalidade e as regras do bot via fluxograma.</p>
        </div>
        <div class="flex gap-4">
            <button @click="$dispatch('save-graph')"
                class="bg-primary text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">save</span>
                Salvar Inteligência
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden h-[70vh] relative bg-slate-50"
        x-data="visualBuilder()" @save-graph.window="saveGraph()">

        <!-- Toolbar -->
        <div class="absolute top-6 left-6 z-10 flex flex-col gap-2">
            <button @click="addNode('personality')" class="btn-node bg-purple-500" title="Personalidade">
                <span class="material-symbols-outlined">psychology</span>
            </button>
            <button @click="addNode('emotion')" class="btn-node bg-rose-500" title="Emoção/Tom">
                <span class="material-symbols-outlined">favorite</span>
            </button>
            <button @click="addNode('instruction')" class="btn-node bg-amber-500" title="Instrução">
                <span class="material-symbols-outlined">rule</span>
            </button>
            <button @click="addNode('context')" class="btn-node bg-blue-500" title="Contexto">
                <span class="material-symbols-outlined">info</span>
            </button>
        </div>

        <!-- Canvas -->
        <div id="canvas" class="w-full h-full relative overflow-hidden cursor-grab active:cursor-grabbing"
            @mousedown="startPan($event)" @mousemove="handleMove($event)" @mouseup="stopAll()" @mouseleave="stopAll()">

            <!-- SVG for Connections -->
            <svg class="absolute inset-0 w-full h-full pointer-events-none">
                <template x-for="conn in connections" :key="'conn-'+conn.from+'-'+conn.to">
                    <line :x1="getNodePoint(conn.from).x" :y1="getNodePoint(conn.from).y" :x2="getNodePoint(conn.to).x"
                        :y2="getNodePoint(conn.to).y" stroke="#cbd5e1" stroke-width="2" stroke-dasharray="4" />
                </template>
                <!-- Ghost connection while linking -->
                <line x-show="linking" :x1="linkFromPoint.x" :y1="linkFromPoint.y" :x2="mousePos.x" :y2="mousePos.y"
                    stroke="#3b82f6" stroke-width="2" />
            </svg>

            <!-- Nodes -->
            <template x-for="node in nodes" :key="node.id">
                <div class="absolute p-4 rounded-2xl shadow-xl border-2 transition-transform select-none"
                    :style="`transform: translate(${node.x}px, ${node.y}px); width: 220px; z-index: ${selectedNode === node.id ? 100 : 10};`"
                    :class="{
                            'bg-purple-50 border-purple-200': node.type === 'personality',
                            'bg-rose-50 border-rose-200': node.type === 'emotion',
                            'bg-amber-50 border-amber-200': node.type === 'instruction',
                            'bg-blue-50 border-blue-200': node.type === 'context',
                            'ring-4 ring-primary/20 border-primary': selectedNode === node.id
                        }" @mousedown.stop="startDrag(node, $event)">

                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg" :class="`text-${getColorClass(node.type)}-500`"
                                x-text="getIcon(node.type)"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400"
                                x-text="node.type"></span>
                        </div>
                        <button @click.stop="removeNode(node.id)" class="text-slate-300 hover:text-red-500">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>

                    <input x-model="node.label" @mousedown.stop
                        class="w-full bg-white border border-slate-100 rounded-lg px-2 py-1 text-[11px] font-black uppercase tracking-tight mb-2 outline-none focus:border-primary">

                    <textarea x-model="node.content" @mousedown.stop
                        class="w-full bg-white border border-slate-100 rounded-lg p-2 text-[10px] font-medium text-slate-600 h-20 resize-none outline-none focus:border-primary"
                        placeholder="Insira as instruções ou contexto aqui..."></textarea>

                    <!-- Connection Points -->
                    <div class="absolute -right-2 top-1/2 -translate-y-1/2 size-4 bg-white border-2 border-slate-200 rounded-full cursor-crosshair hover:bg-primary hover:border-primary transition-colors flex items-center justify-center"
                        @mousedown.stop="startLink(node, $event)">
                        <div class="size-1.5 bg-slate-200 rounded-full"></div>
                    </div>
                    <div class="absolute -left-2 top-1/2 -translate-y-1/2 size-4 bg-white border-2 border-slate-200 rounded-full cursor-crosshair hover:bg-primary hover:border-primary transition-colors flex items-center justify-center"
                        @mouseup.stop="endLink(node)">
                        <div class="size-1.5 bg-slate-200 rounded-full"></div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Help Overlay -->
        <div
            class="absolute bottom-6 left-6 bg-white/80 backdrop-blur px-4 py-2 rounded-xl text-[9px] font-bold text-slate-400 border border-slate-100 uppercase tracking-widest">
            💡 Arraste e ligue os pontos para combinar as regras.
        </div>
    </div>

    <style>
        .btn-node {
            @apply size-12 rounded-2xl text-white shadow-lg flex items-center justify-center transition-all transform hover:scale-110 active:scale-95;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @push('scripts')
        <script>
            function visualBuilder() {
                return {
                    nodes: @json($nodes),
                    connections: @json($connections->map(fn($c) => ['from' => $c->from_node_id, 'to' => $c->to_node_id])),
                    dragging: null,
                    selectedNode: null,
                    pan: { x: 0, y: 0 },
                    linking: null,
                    mousePos: { x: 0, y: 0 },
                    linkFromPoint: { x: 0, y: 0 },

                    addNode(type) {
                        const id = Date.now();
                        this.nodes.push({
                            id: id,
                            type: type,
                            label: 'Novo Nó ' + type.charAt(0).toUpperCase() + type.slice(1),
                            content: '',
                            x: 100 + (this.nodes.length * 20),
                            y: 100 + (this.nodes.length * 20)
                        });
                    },

                    removeNode(id) {
                        this.nodes = this.nodes.filter(n => n.id !== id);
                        this.connections = this.connections.filter(c => c.from !== id && c.to !== id);
                    },

                    getIcon(type) {
                        const icons = {
                            personality: 'psychology',
                            emotion: 'favorite',
                            instruction: 'rule',
                            context: 'info'
                        };
                        return icons[type] || 'circle';
                    },

                    getColorClass(type) {
                        const colors = {
                            personality: 'purple',
                            emotion: 'rose',
                            instruction: 'amber',
                            context: 'blue'
                        };
                        return colors[type];
                    },

                    getNodePoint(id) {
                        const node = this.nodes.find(n => n.id === id);
                        if (!node) return { x: 0, y: 0 };
                        // Anchor to right for from, left for to is handled by dynamic draw
                        return { x: node.x + 110, y: node.y + 70 };
                    },

                    startDrag(node, e) {
                        this.dragging = node;
                        this.selectedNode = node.id;
                        this.offset = {
                            x: e.clientX - node.x,
                            y: e.clientY - node.y
                        };
                    },

                    startPan(e) {
                        if (e.target.id === 'canvas') {
                            // Implement panning if needed
                        }
                    },

                    startLink(node, e) {
                        this.linking = node.id;
                        this.linkFromPoint = { x: node.x + 220, y: node.y + 70 };
                        this.mousePos = { x: e.clientX, y: e.clientY };
                    },

                    endLink(node) {
                        if (this.linking && this.linking !== node.id) {
                            // Check if connection already exists
                            const exists = this.connections.some(c => c.from === this.linking && c.to === node.id);
                            if (!exists) {
                                this.connections.push({ from: this.linking, to: node.id });
                            }
                        }
                        this.linking = null;
                    },

                    handleMove(e) {
                        if (this.dragging) {
                            this.dragging.x = e.clientX - this.offset.x;
                            this.dragging.y = e.clientY - this.offset.y;
                        }
                        if (this.linking) {
                            this.mousePos = {
                                x: e.clientX - document.getElementById('canvas').getBoundingClientRect().left,
                                y: e.clientY - document.getElementById('canvas').getBoundingClientRect().top
                            };
                            // linkFromPoint is also relative
                        }
                    },

                    stopAll() {
                        this.dragging = null;
                        this.linking = null;
                    },

                    async saveGraph() {
                        try {
                            const response = await fetch('{{ route('admin.ia.intelligence.save') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    nodes: this.nodes,
                                    connections: this.connections
                                })
                            });
                            const data = await response.json();
                            if (data.success) {
                                alert('Inteligência salva com sucesso!');
                            } else {
                                alert('Erro ao salvar: ' + data.message);
                            }
                        } catch (error) {
                            alert('Erro ao conectar com o servidor.');
                        }
                    }
                };
            }
        </script>
    @endpush
@endsection