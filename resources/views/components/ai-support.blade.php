<div x-data="aiSupport()" x-show="isLoaded" x-cloak class="fixed bottom-6 right-6 z-[9999] font-display">
    <!-- Chat Button -->
    <button @click="toggleChat()"
        class="group relative size-16 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 transform hover:scale-110 active:scale-95 z-20 overflow-hidden">
        <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        </div>
        <span class="material-symbols-outlined text-3xl transition-all duration-300"
            :class="isOpen ? 'rotate-90 opacity-0' : 'rotate-0 opacity-100'">smart_toy</span>
        <span class="material-symbols-outlined text-3xl absolute transition-all duration-300"
            :class="isOpen ? 'rotate-0 opacity-100' : '-rotate-90 opacity-0'" x-cloak>close</span>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="absolute bottom-20 right-0 w-[92vw] sm:w-[480px] max-h-[700px] h-[75vh] bg-white rounded-[2.5rem] shadow-[0_30px_80px_-20px_rgba(0,0,0,0.2)] border border-slate-100 flex flex-col overflow-hidden z-10"
        x-cloak>

        <!-- Header -->
        <div class="px-6 py-4 bg-secondary text-white flex items-center justify-between relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-[60px] rounded-full translate-x-1/2 -translate-y-1/2">
            </div>
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-xl bg-primary flex items-center justify-center shadow-lg shadow-primary/30">
                    <span class="material-symbols-outlined text-xl">smart_toy</span>
                </div>
                <div>
                    <h3 class="font-black uppercase italic tracking-tighter text-xs">Assistente Sisters</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="size-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-[9px] uppercase font-black tracking-widest text-slate-400">Online e
                            Rápido</span>
                    </div>
                </div>
            </div>
            <button @click="clearChat()" title="Limpar Conversa"
                class="flex items-center gap-1.5 text-slate-400 hover:text-white transition-all bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-xl border border-white/10">
                <span class="material-symbols-outlined text-sm">delete_sweep</span>
                <span class="text-[10px] font-black uppercase tracking-wider">Limpar</span>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages"
            class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-slate-50/50 scroll-smooth custom-markdown">
            <!-- Welcome Message -->
            <div class="flex items-start gap-3">
                <div
                    class="size-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                    <span class="material-symbols-outlined text-lg text-primary">smart_toy</span>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[90%]">
                    <p class="text-xs font-bold text-slate-700 leading-relaxed italic">
                        Olá corredor! 👋 Sou o cérebro digital da Sisters. Como posso turbinar sua experiência hoje?
                    </p>
                </div>
            </div>

            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex items-start gap-3'">
                    <div x-show="msg.role === 'assistant'"
                        class="size-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                        <span class="material-symbols-outlined text-lg" x-text="'smart_toy'"></span>
                    </div>
                    <div :class="msg.role === 'user' ? 'bg-primary text-white rounded-tr-none px-4 py-3' : 'bg-white text-slate-700 rounded-tl-none p-4'"
                        class="rounded-2xl shadow-sm border border-slate-100/50 max-w-[90%]">
                        <div class="text-[12px] font-bold leading-tight whitespace-pre-wrap markdown-content"
                            x-html="msg.content"></div>
                    </div>
                </div>
            </template>

            <!-- WhatsApp Transition -->
            <div x-show="showWhatsApp" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="bg-green-50/50 border border-green-100 p-5 rounded-2xl flex flex-col items-center gap-3 text-center mt-2">
                <div class="size-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                    <span class="material-symbols-outlined text-2xl">support_agent</span>
                </div>
                <div>
                    <h4 class="text-green-800 font-black uppercase text-[10px] tracking-widest">Suporte Humano Ativado
                    </h4>
                    <p class="text-green-700 text-[9px] font-bold">Equipe pronta no WhatsApp.</p>
                </div>
                <a href="https://wa.me/556199889363" target="_blank"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-green-600 text-white hover:bg-green-700 transition-all shadow-md transform active:scale-95 group">
                    <span
                        class="material-symbols-outlined text-xl transition-transform group-hover:scale-110">chat</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Falar no WhatsApp</span>
                </a>
            </div>

            <!-- Loading Indicator -->
            <div x-show="isLoading" class="flex items-start gap-3" x-cloak>
                <div
                    class="size-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                    <span class="material-symbols-outlined text-lg animate-bounce">smart_toy</span>
                </div>
                <div class="bg-white p-4 rounded-xl rounded-tl-none shadow-sm border border-slate-100">
                    <div class="flex gap-1">
                        <span class="size-1.5 bg-slate-200 rounded-full animate-bounce"></span>
                        <span class="size-1.5 bg-slate-200 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                        <span class="size-1.5 bg-slate-200 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / Input -->
        <div class="p-5 bg-white border-t border-slate-100">
            <form @submit.prevent="sendMessage()" class="relative">
                <input x-model="userInput" type="text" placeholder="Faça sua pergunta..."
                    class="w-full bg-slate-50 border border-slate-200 focus:border-primary/50 focus:bg-white rounded-2xl pl-5 pr-14 py-3 text-xs font-bold transition-all outline-none"
                    :disabled="isLoading">
                <button type="submit"
                    class="absolute right-1.5 top-1.5 size-9 bg-secondary text-white rounded-xl flex items-center justify-center hover:bg-black transition-all transform active:scale-90 disabled:opacity-50"
                    :disabled="isLoading || !userInput.trim()">
                    <span class="material-symbols-outlined text-xl">send</span>
                </button>
            </form>
            <div class="mt-3 flex items-center justify-center">
                <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 text-center leading-none">
                    Powered by Sisters AI</p>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    .markdown-content p {
        margin-bottom: 0.5rem !important;
    }

    .markdown-content p:last-child {
        margin-bottom: 0 !important;
    }

    .markdown-content ul,
    .markdown-content ol {
        margin-bottom: 0.5rem !important;
        padding-left: 1rem !important;
    }

    .markdown-content li {
        margin-bottom: 0.25rem !important;
    }

    .markdown-content strong {
        color: inherit !important;
        font-weight: 800 !important;
    }

    .markdown-content hr {
        margin: 0.75rem 0 !important;
        border-color: rgba(0, 0, 0, 0.05) !important;
    }

    .markdown-content a {
        color: #2563eb !important;
        /* blue-600 */
        text-decoration: underline !important;
        font-weight: bold !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    function aiSupport() {
        return {
            isOpen: false,
            isLoading: false,
            userInput: '',
            messages: JSON.parse(localStorage.getItem('chat_history') || '[]'),
            isLoaded: false,
            showWhatsApp: false,

            init() {
                if (document.readyState === 'complete') {
                    this.isLoaded = true;
                } else {
                    window.addEventListener('load', () => {
                        this.isLoaded = true;
                    });
                }
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    setTimeout(() => {
                        this.scrollToBottom();
                    }, 100);
                }
            },

            clearChat() {
                if (confirm('Limpar histórico de conversa?')) {
                    this.messages = [];
                    this.showWhatsApp = false;
                    localStorage.removeItem('chat_history');
                }
            },

            async sendMessage() {
                if (!this.userInput.trim() || this.isLoading) return;

                const message = this.userInput.trim();
                this.messages.push({ role: 'user', content: message });
                this.userInput = '';
                this.isLoading = true;

                setTimeout(() => this.scrollToBottom(), 50);

                try {
                    const response = await fetch('{{ route('ai.support.chat') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: message,
                            history: this.messages.slice(0, -1).map(m => ({
                                role: m.role,
                                content: m.content.replace(/<[^>]*>?/gm, '') // Strip HTML for API
                            }))
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Use marked to render markdown for better readability
                        const htmlResponse = marked.parse(data.response);
                        this.messages.push({ role: 'assistant', content: htmlResponse });

                        if (data.show_whatsapp) {
                            this.showWhatsApp = true;
                        }
                    } else {
                        this.messages.push({
                            role: 'assistant',
                            content: data.message || 'Desculpe, ocorreu um erro. Tente novamente mais tarde.'
                        });
                    }

                    // Persist history
                    localStorage.setItem('chat_history', JSON.stringify(this.messages));

                } catch (error) {
                    this.messages.push({
                        role: 'assistant',
                        content: 'Desculpe, não consegui me conectar ao servidor. Verifique sua conexão.'
                    });
                } finally {
                    this.isLoading = false;
                    setTimeout(() => this.scrollToBottom(), 50);
                }
            },

            scrollToBottom() {
                const chatBody = document.getElementById('chat-messages');
                if (chatBody) {
                    chatBody.scrollTop = chatBody.scrollHeight;
                }
            }
        }
    }
</script>