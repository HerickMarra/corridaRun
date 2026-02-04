@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.athletes.index') }}"
            class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-primary transition-all flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Voltar para listagem
        </a>
        <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Editar <span
                class="text-primary">Atleta</span></h2>
        <p class="text-slate-500 text-sm font-medium">Atualize os dados cadastrais e de contato do corredor.</p>
    </div>

    <form action="{{ route('admin.athletes.update', $athlete->id) }}" method="POST" class="space-y-8 pb-20">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Informações Pessoais -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                        Informações Pessoais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome
                                Completo</label>
                            <input name="name" value="{{ old('name', $athlete->name) }}" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="Nome do atleta" type="text" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">CPF</label>
                            <input name="cpf" value="{{ old('cpf', $athlete->cpf) }}"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="000.000.000-00" type="text" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Data de
                                Nascimento</label>
                            <input name="birth_date"
                                value="{{ old('birth_date', $athlete->birth_date ? $athlete->birth_date->format('Y-m-d') : '') }}"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                type="date" />
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                        Endereço</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1 space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">CEP</label>
                            <input name="zip_code" value="{{ old('zip_code', $athlete->zip_code) }}"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="00000-000" type="text" />
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Logradouro</label>
                            <input name="address" value="{{ old('address', $athlete->address) }}"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="Rua, número, bairro..." type="text" />
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cidade</label>
                            <input name="city" value="{{ old('city', $athlete->city) }}"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="Cidade" type="text" />
                        </div>
                        <div class="md:col-span-1 space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Estado
                                (UF)</label>
                            <input name="state" value="{{ old('state', $athlete->state) }}"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="SP" maxlength="2" type="text" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-8">
                <!-- Contato -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                        Contato</h3>
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">E-mail</label>
                            <input name="email" value="{{ old('email', $athlete->email) }}" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="email@atleta.com" type="email" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">WhatsApp /
                                Telefone</label>
                            <input name="phone" value="{{ old('phone', $athlete->phone) }}"
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                placeholder="(00) 00000-0000" type="text" />
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-6 rounded-2xl text-sm font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Salvar Alterações
                </button>
            </div>
        </div>
    </form>
@endsection