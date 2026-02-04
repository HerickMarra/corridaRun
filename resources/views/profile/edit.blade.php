@extends('layouts.app')

@section('content')
    <main class="pt-40 pb-32 px-6 lg:px-12 bg-white">
        <div class="max-w-4xl mx-auto">
            @if(session('success'))
                <div
                    class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-xl">check_circle</span>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <section class="flex flex-col items-center mb-24 text-center">
                <div class="relative group mb-6">
                    <div
                        class="size-48 rounded-full overflow-hidden border-4 border-white shadow-2xl ring-1 ring-slate-100">
                        @if($user->profile_photo)
                            <img id="profilePhotoPreview" alt="Athlete Profile" class="w-full h-full object-cover"
                                src="{{ asset('storage/' . $user->profile_photo) }}" />
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center">
                                <span class="text-6xl font-black text-primary">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <label for="photoInput"
                        class="absolute bottom-2 right-2 bg-white size-10 rounded-full shadow-lg flex items-center justify-center border border-slate-100 hover:bg-slate-50 transition-colors text-slate-600 cursor-pointer">
                        <span class="material-symbols-outlined text-xl">photo_camera</span>
                    </label>
                    <input type="file" id="photoInput" class="hidden" accept="image/*" onchange="uploadPhoto(this)">
                </div>
                <button onclick="document.getElementById('photoInput').click()"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition-colors">
                    Trocar Foto de Perfil
                </button>
                <h1 class="mt-8 text-4xl font-black text-secondary uppercase italic leading-none tracking-tighter">
                    Configurações do Perfil</h1>
                <p class="mt-2 text-slate-400 font-medium text-sm">Membro desde {{ $user->created_at->format('F Y') }}</p>
            </section>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-24">
                @csrf
                @method('PUT')

                <section>
                    <h2 class="text-2xl font-black uppercase italic tracking-tight mb-12 border-b border-slate-100 pb-4">
                        Dados Pessoais</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-10">
                        <div class="flex flex-col">
                            <label for="name">Nome Completo</label>
                            <input id="name" name="name" placeholder="Ex: João Silva" type="text"
                                value="{{ old('name', $user->name) }}" required />
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label for="email">E-mail</label>
                            <input id="email" name="email" placeholder="seu@email.com" type="email"
                                value="{{ old('email', $user->email) }}" required />
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label for="cpf">CPF (Inalterável)</label>
                            <input class="bg-transparent border-slate-100 text-slate-400 cursor-not-allowed" disabled
                                id="cpf" type="text" value="{{ $user->cpf ?? 'Não informado' }}" />
                        </div>
                        <div class="flex flex-col">
                            <label for="birth_date">Data de Nascimento</label>
                            <input id="birth_date" name="birth_date" placeholder="DD/MM/AAAA" type="date"
                                value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" />
                            @error('birth_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label for="team">Equipe / Assessoria</label>
                            <input id="team" name="team" placeholder="Sua equipe de corrida" type="text"
                                value="{{ old('team', $user->team) }}" />
                            @error('team') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label for="phone">Telefone de Contato</label>
                            <input id="phone" name="phone" placeholder="(00) 00000-0000" type="text"
                                value="{{ old('phone', $user->phone) }}" />
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-black uppercase italic tracking-tight mb-12 border-b border-slate-100 pb-4">
                        Medidas do Kit</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-10">
                        <div class="flex flex-col">
                            <label for="shirt_size">Tamanho de Camiseta</label>
                            <select id="shirt_size" name="shirt_size">
                                <option value="">Selecione</option>
                                <option value="PP" {{ old('shirt_size', $user->shirt_size) == 'PP' ? 'selected' : '' }}>
                                    MASCULINO PP</option>
                                <option value="P" {{ old('shirt_size', $user->shirt_size) == 'P' ? 'selected' : '' }}>
                                    MASCULINO P</option>
                                <option value="M" {{ old('shirt_size', $user->shirt_size) == 'M' ? 'selected' : '' }}>
                                    MASCULINO M</option>
                                <option value="G" {{ old('shirt_size', $user->shirt_size) == 'G' ? 'selected' : '' }}>
                                    MASCULINO G</option>
                                <option value="GG" {{ old('shirt_size', $user->shirt_size) == 'GG' ? 'selected' : '' }}>
                                    MASCULINO GG</option>
                                <option value="F-P" {{ old('shirt_size', $user->shirt_size) == 'F-P' ? 'selected' : '' }}>
                                    FEMININO P</option>
                                <option value="F-M" {{ old('shirt_size', $user->shirt_size) == 'F-M' ? 'selected' : '' }}>
                                    FEMININO M</option>
                                <option value="F-G" {{ old('shirt_size', $user->shirt_size) == 'F-G' ? 'selected' : '' }}>
                                    FEMININO G</option>
                            </select>
                            @error('shirt_size') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label for="shoe_size">Tamanho do Tênis (BR)</label>
                            <select id="shoe_size" name="shoe_size">
                                <option value="">Selecione</option>
                                @for($size = 34; $size <= 48; $size++)
                                    <option value="{{ $size }}" {{ old('shoe_size', $user->shoe_size) == $size ? 'selected' : '' }}>{{ $size }} BR</option>
                                @endfor
                            </select>
                            @error('shoe_size') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-black uppercase italic tracking-tight mb-12 border-b border-slate-100 pb-4">
                        Saúde</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-10">
                        <div class="flex flex-col">
                            <label for="blood_type">Tipo Sanguíneo</label>
                            <select id="blood_type" name="blood_type">
                                <option value="">Selecione</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                    <option value="{{ $type }}" {{ old('blood_type', $user->blood_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('blood_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label for="emergency_contact_name">Contato de Emergência</label>
                            <input id="emergency_contact_name" name="emergency_contact_name" placeholder="Nome do contato"
                                type="text" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" />
                            @error('emergency_contact_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <label for="emergency_contact_phone">Telefone de Emergência</label>
                            <input id="emergency_contact_phone" name="emergency_contact_phone" placeholder="(00) 00000-0000"
                                type="text" value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" />
                            @error('emergency_contact_phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <label for="allergies">Alergias ou Observações</label>
                            <input id="allergies" name="allergies" placeholder="Descreva se houver" type="text"
                                value="{{ old('allergies', $user->allergies) }}" />
                            @error('allergies') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </section>

                <div class="pt-12 flex items-center justify-center gap-6 border-t border-slate-50">
                    <a href="{{ route('dashboard') }}"
                        class="px-8 py-4 text-slate-400 font-black text-[11px] uppercase tracking-[0.2em] hover:text-secondary transition-all">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-primary text-white px-12 py-5 rounded-full font-black text-[12px] uppercase tracking-[0.2em] hover:bg-blue-700 transition-all shadow-xl shadow-primary/20">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function uploadPhoto(input) {
            if (input.files && input.files[0]) {
                const formData = new FormData();
                formData.append('photo', input.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                // Show loading state
                showToast('Enviando foto...', 'info');

                fetch('{{ route('profile.photo.upload') }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const preview = document.getElementById('profilePhotoPreview');
                            if (preview) {
                                preview.src = data.photo_url;
                            } else {
                                // If no preview exists, reload to show the new photo
                                location.reload();
                            }
                            showToast('Foto atualizada com sucesso! ✓', 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Erro ao fazer upload da foto', 'error');
                    });
            }
        }

        function showToast(message, type = 'info') {
            // Remove existing toast if any
            const existingToast = document.getElementById('toast-notification');
            if (existingToast) {
                existingToast.remove();
            }

            // Create toast element
            const toast = document.createElement('div');
            toast.id = 'toast-notification';
            toast.className = 'fixed top-24 right-6 z-50 transform transition-all duration-500 ease-out';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';

            const bgColors = {
                success: 'bg-green-50 border-green-200 text-green-700',
                error: 'bg-red-50 border-red-200 text-red-700',
                info: 'bg-blue-50 border-blue-200 text-blue-700'
            };

            const icons = {
                success: 'check_circle',
                error: 'error',
                info: 'info'
            };

            toast.innerHTML = `
                    <div class="${bgColors[type]} border px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 min-w-[300px]">
                        <span class="material-symbols-outlined text-xl">${icons[type]}</span>
                        <p class="text-sm font-bold">${message}</p>
                    </div>
                `;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
            }, 10);

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.style.transform = 'translateY(-20px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>

    <style>
        input,
        select,
        textarea {
            @apply border-slate-200 focus:border-primary focus:ring-0 text-sm font-medium py-3 px-0 border-0 border-b transition-all placeholder:text-slate-300;
        }

        label {
            @apply text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-1 block;
        }
    </style>
@endsection