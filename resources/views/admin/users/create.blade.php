@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}"
                class="size-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Novo <span
                        class="text-primary">Administrador</span></h2>
                <p class="text-slate-500 text-sm font-medium">Cadastre um novo membro para a equipe de gestão.</p>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Informações Básicas -->
                <div class="md:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">Dados
                        de Acesso</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome
                                Completo</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                placeholder="Ex: João Silva">
                            @error('name') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">{{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Email
                                Profissional</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                placeholder="email@empresa.com">
                            @error('email') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">{{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cargo /
                                Role</label>
                            <select name="role" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none cursor-pointer">
                                <option value="super-admin">Super Admin (Acesso Total)</option>
                                <option value="admin" selected>Administrador Padrão</option>
                                <option value="gestor">Gestor (Financeiro/Vendas)</option>
                                <option value="organizador">Organizador (Apenas Provas)</option>
                            </select>
                            @error('role') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">{{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Status da
                                Conta</label>
                            <div class="flex items-center h-[52px]">
                                <span
                                    class="bg-green-50 text-green-600 text-[10px] font-black px-4 py-2 rounded-lg border border-green-100 uppercase tracking-widest">
                                    Ativo Imediatamente
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Segurança -->
                <div class="md:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-black uppercase italic tracking-tight mb-6 border-b border-slate-50 pb-4">
                        Segurança</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Senha
                                Temporária</label>
                            <input type="password" name="password" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                placeholder="********">
                            @error('password') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 ml-1">
                            {{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Confirmar
                                Senha</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-slate-50 border-transparent rounded-xl px-5 py-4 text-sm font-bold focus:bg-white transition-all outline-none"
                                placeholder="********">
                        </div>
                    </div>
                    <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] text-slate-500 font-medium">
                            <span class="font-bold text-primary mr-1 italic">Dica:</span> O novo administrador poderá
                            alterar sua senha após o primeiro acesso através do perfil.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.users.index') }}"
                    class="bg-white border border-slate-200 text-slate-600 px-8 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                    Cancelar
                </a>
                <button type="submit"
                    class="bg-primary text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/20">
                    Criar Administrador
                </button>
            </div>
        </form>
    </div>
@endsection