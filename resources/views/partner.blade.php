@extends('layouts.app')

@section('title', 'Seja um Parceiro')

@section('content')
    <section class="relative">
        <div class="relative w-full h-[500px] overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center"
                style='background-image: url("https://images.unsplash.com/photo-1452626038306-9aae5e071dd3?w=1920");'></div>
            <div class="absolute inset-0"
                style="background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.6) 100%);"></div>
            <div class="relative z-10 h-full max-w-[1440px] mx-auto px-6 lg:px-12 flex flex-col justify-center">
                <span
                    class="inline-block py-2 px-5 bg-primary text-white text-[10px] font-bold tracking-[0.2em] uppercase mb-6 self-start rounded-md">
                    Seja um Parceiro
                </span>
                <h1
                    class="text-white text-5xl md:text-7xl font-black leading-[1] tracking-tighter uppercase italic max-w-3xl">
                    ORGANIZE SEU EVENTO CONOSCO
                </h1>
                <p class="text-white/90 text-xl mt-6 max-w-xl font-medium">
                    Leve sua prova para o próximo nível com a infraestrutura tecnológica e o marketing da maior plataforma
                    de running do país.
                </p>
            </div>
        </div>
    </section>

    <main class="bg-white">
        <section class="py-24 px-6 lg:px-12">
            <div class="max-w-[1440px] mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-black text-secondary uppercase italic">Por que escolher a Sisters Esportes?
                    </h2>
                    <div class="w-20 h-1.5 bg-primary mx-auto mt-4"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="text-center group">
                        <div
                            class="size-20 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary group-hover:text-white transition-all border border-slate-100 shadow-sm">
                            <span class="material-symbols-outlined text-4xl">assignment_turned_in</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4 uppercase italic">Gestão de Inscrições</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Controle total sobre lotes, cupons, kits e
                            pagamentos com repasses rápidos e seguros.</p>
                    </div>
                    <div class="text-center group">
                        <div
                            class="size-20 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary group-hover:text-white transition-all border border-slate-100 shadow-sm">
                            <span class="material-symbols-outlined text-4xl">campaign</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4 uppercase italic">Marketing Especializado</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Alcance direto a milhares de atletas ativos
                            através de nossa base segmentada e canais sociais.</p>
                    </div>
                    <div class="text-center group">
                        <div
                            class="size-20 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary group-hover:text-white transition-all border border-slate-100 shadow-sm">
                            <span class="material-symbols-outlined text-4xl">timer</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4 uppercase italic">Tecnologia de Cronometragem</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Integração nativa com os melhores sistemas de
                            cronometragem para resultados em tempo real.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 px-6 lg:px-12 bg-slate-50">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-3xl p-8 md:p-16 shadow-lg">
                    <div class="mb-12">
                        <h2 class="text-3xl font-black text-secondary uppercase italic mb-2">Seja um Parceiro</h2>
                        <p class="text-slate-500 font-medium">Preencha os dados abaixo e nossa equipe comercial entrará em
                            contato em até 24h.</p>
                    </div>
                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-slate-700 ml-1">Nome da
                                    Empresa/Organizador</label>
                                <input
                                    class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                    placeholder="Ex: Sport Events Ltda" type="text" name="company_name" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-slate-700 ml-1">E-mail
                                    Corporativo</label>
                                <input
                                    class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                    placeholder="contato@empresa.com.br" type="email" name="email" required />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-slate-700 ml-1">Tipo de
                                    Evento</label>
                                <select
                                    class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm focus:ring-primary focus:border-primary transition-all"
                                    name="event_type" required>
                                    <option value="" disabled selected>Selecione a modalidade</option>
                                    <option>Rua</option>
                                    <option>Trail</option>
                                    <option>Kids</option>
                                    <option>Obstáculos</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-slate-700 ml-1">Estimativa de
                                    Participantes</label>
                                <select
                                    class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm focus:ring-primary focus:border-primary transition-all"
                                    name="participants" required>
                                    <option value="" disabled selected>Quantidade esperada</option>
                                    <option>Até 500</option>
                                    <option>500 a 1.500</option>
                                    <option>1.500 a 3.000</option>
                                    <option>Acima de 3.000</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-slate-700 ml-1">Mensagem</label>
                            <textarea
                                class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400 resize-none"
                                placeholder="Conte-nos um pouco sobre seu evento..." rows="4" name="message"></textarea>
                        </div>
                        <div class="pt-4">
                            <button
                                class="w-full bg-primary hover:bg-blue-600 text-white font-black py-5 rounded-xl uppercase tracking-[0.1em] transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-3"
                                type="submit">
                                Enviar Solicitação de Parceria
                                <span class="material-symbols-outlined">send</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection