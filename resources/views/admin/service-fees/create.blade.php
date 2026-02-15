@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.service-fees.index') }}"
            class="text-primary flex items-center gap-2 text-sm font-black uppercase tracking-widest hover:gap-3 transition-all mb-4">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Voltar para lista
        </a>
        <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Nova <span
                class="text-primary">Taxa de Serviço</span></h2>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm max-w-2xl">
        <form action="{{ route('admin.service-fees.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name"
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nome da
                        Taxa</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold text-slate-700"
                        placeholder="Ex: Taxa de Conveniência">
                    @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="type"
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tipo</label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold text-slate-700">
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Valor Fixo (R$)</option>
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentual (%)
                            </option>
                        </select>
                        @error('type') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="value"
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Valor</label>
                        <input type="number" name="value" id="value" value="{{ old('value') }}" step="0.01" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold text-slate-700"
                            placeholder="0.00">
                        @error('value') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div
                            class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                        </div>
                    </div>
                    <span class="text-sm font-bold text-slate-600">Taxa Ativa (aplicar no checkout)</span>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-primary text-white py-4 rounded-xl text-sm font-black uppercase tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20">
                        Salvar Taxa
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection