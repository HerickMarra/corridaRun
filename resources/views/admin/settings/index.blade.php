@extends('layouts.admin')

@section('content')
    <div class="mb-8 items-center flex justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase italic tracking-tighter">Configurações do <span
                    class="text-primary">Sistema</span></h2>
            <p class="text-slate-500 text-sm font-medium">Gerencie as preferências globais da plataforma.</p>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden"
        x-data="{ activeTab: 'general' }">
        <!-- Tabs Header -->
        <div class="flex border-b border-slate-50 bg-slate-50/50 p-2">
            <button @click="activeTab = 'general'"
                :class="activeTab === 'general' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                class="px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all outline-none">
                Geral
            </button>
            <button @click="activeTab = 'payment'"
                :class="activeTab === 'payment' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                class="px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all outline-none">
                Pagamentos
            </button>
            <button @click="activeTab = 'social'"
                :class="activeTab === 'social' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                class="px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all outline-none">
                Redes Sociais
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-10">
            @csrf
            @method('PUT')

            <!-- General Settings -->
            <div x-show="activeTab === 'general'" class="space-y-8">
                @foreach($settings['general'] ?? [] as $setting)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start pb-8 border-b border-slate-50 last:border-0">
                        <div>
                            <h4 class="text-sm font-black text-slate-800 uppercase italic tracking-tight">{{ $setting->label }}
                            </h4>
                            <p class="text-xs text-slate-400 font-medium mt-1">{{ $setting->description }}</p>
                        </div>
                        <div class="lg:col-span-2">
                            @if($setting->type === 'text')
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:bg-white transition-all focus:ring-primary">
                            @elseif($setting->type === 'file')
                                <div class="flex items-center gap-6">
                                    @if($setting->value)
                                        <img src="{{ asset('storage/' . $setting->value) }}"
                                            class="size-20 rounded-2xl object-cover border border-slate-100">
                                    @endif
                                    <input type="file" name="{{ $setting->key }}"
                                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-primary/5 file:text-primary hover:file:bg-primary hover:file:text-white transition-all">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payment Settings -->
            <div x-show="activeTab === 'payment'" class="space-y-8">
                @foreach($settings['payment'] ?? [] as $setting)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start pb-8 border-b border-slate-50 last:border-0">
                        <div>
                            <h4 class="text-sm font-black text-slate-800 uppercase italic tracking-tight">{{ $setting->label }}
                            </h4>
                            <p class="text-xs text-slate-400 font-medium mt-1">{{ $setting->description }}</p>
                        </div>
                        <div class="lg:col-span-2">
                            @if($setting->type === 'number')
                                <div class="relative max-w-xs">
                                    <input type="number" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:bg-white transition-all focus:ring-primary">
                                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                                </div>
                            @elseif($setting->type === 'text')
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:bg-white transition-all focus:ring-primary">
                            @elseif($setting->type === 'password')
                                <input type="password" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:bg-white transition-all focus:ring-primary">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Social Settings -->
            <div x-show="activeTab === 'social'" class="space-y-8">
                @foreach($settings['social'] ?? [] as $setting)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start pb-8 border-b border-slate-50 last:border-0">
                        <div>
                            <h4 class="text-sm font-black text-slate-800 uppercase italic tracking-tight">{{ $setting->label }}
                            </h4>
                            <p class="text-xs text-slate-400 font-medium mt-1">{{ $setting->description }}</p>
                        </div>
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:bg-white transition-all focus:ring-primary pl-14">
                                <span
                                    class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-400">link</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit"
                    class="bg-primary text-white px-12 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/20">
                    Salvar Configurações
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush
@endsection