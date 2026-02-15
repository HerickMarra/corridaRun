@extends('layouts.app')

@section('title', 'Checkout - ' . $event->name)

@section('content')
    <main class="pt-32 pb-24 px-6 lg:px-12 bg-background-soft">
        <div class="max-w-[1200px] mx-auto">
            <div class="flex flex-col lg:flex-row gap-12">
                <div class="lg:flex-1 space-y-8">
                    <div>
                        <h1 class="text-3xl font-black uppercase italic tracking-tighter mb-2">Finalizar <span
                                class="text-primary">Inscrição</span></h1>
                        <p class="text-slate-500 text-sm font-medium">Complete seus dados para garantir sua vaga na largada.
                        </p>
                    </div>

                    <form action="{{ route('checkout.process', $category->id) }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="coupon_code" id="hidden-coupon-code" value="{{ old('coupon_code') }}">

                        {{-- Error Messages --}}
                        @if ($errors->any())
                            <div class="bg-red-50 border-2 border-red-200 rounded-3xl p-6 mb-8">
                                <div class="flex items-start gap-4">
                                    <span class="material-symbols-outlined text-red-600 text-2xl flex-shrink-0">error</span>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-black uppercase tracking-widest text-red-900 mb-2">Erro ao
                                            processar inscrição</h3>
                                        <ul class="space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li class="text-sm text-red-700">• {{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <section class="bg-white rounded-3xl p-8 card-shadow border border-slate-50 mb-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="size-10 rounded-xl bg-slate-50 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <h2 class="text-lg font-black uppercase italic">Identificação do Atleta</h2>
                            </div>

                            <!-- Resumo de Dados Preenchidos -->
                            <div class="bg-slate-50 rounded-2xl p-4 mb-6 flex flex-wrap gap-4 items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Atleta</p>
                                    <p class="text-sm font-bold text-slate-700">{{ auth()->user()->name }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">E-mail</p>
                                    <p class="text-sm font-bold text-slate-700">{{ auth()->user()->email }}</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- CPF (Sempre visível/readonly) -->
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">CPF</label>
                                    <div class="relative">
                                        <input name="cpf" id="cpf-input" required
                                            class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all @if(auth()->user()->cpf) opacity-70 cursor-not-allowed @endif"
                                            placeholder="000.000.000-00" type="text" value="{{ auth()->user()->cpf }}"
                                            maxlength="14" @if(auth()->user()->cpf) readonly @endif />
                                        @if(auth()->user()->cpf)
                                            <div class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"
                                                title="CPF já cadastrado e bloqueado para edição">
                                                <span class="material-symbols-outlined text-sm">lock</span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-xs text-red-500 hidden" id="cpf-error">CPF inválido.</p>
                                </div>

                                <!-- Data de Nascimento (Condicional) -->
                                @if(!auth()->user()->birth_date)
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Data
                                            de Nascimento <span class="text-red-500">*</span></label>
                                        <input name="birth_date" type="date" required
                                            class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all" />
                                    </div>
                                @endif

                                <!-- Gênero (Condicional) -->
                                @if(!auth()->user()->gender)
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Gênero
                                            <span class="text-red-500">*</span></label>
                                        <select name="gender" required
                                            class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all appearance-none">
                                            <option value="">Selecione...</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Feminino</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </section>

                        @if($event->customFields->count() > 0)
                            <section class="bg-white rounded-3xl p-8 card-shadow border border-slate-50 mb-8">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="size-10 rounded-xl bg-slate-50 flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">quiz</span>
                                    </div>
                                    <h2 class="text-lg font-black uppercase italic">Informações Adicionais</h2>
                                </div>
                                <div class="grid grid-cols-1 gap-6">
                                    @foreach($event->customFields as $field)
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">
                                                {{ $field->label }}
                                                @if($field->is_required) <span class="text-red-500">*</span> @endif
                                            </label>

                                            @if($field->type === 'text')
                                                <input name="custom_responses[{{ $field->id }}]"
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    {{ $field->is_required ? 'required' : '' }} type="text" />
                                            @elseif($field->type === 'number')
                                                <input name="custom_responses[{{ $field->id }}]"
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    {{ $field->is_required ? 'required' : '' }} type="number" />
                                            @elseif($field->type === 'textarea')
                                                <textarea name="custom_responses[{{ $field->id }}]" rows="3"
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    {{ $field->is_required ? 'required' : '' }}></textarea>
                                            @elseif($field->type === 'select')
                                                <select name="custom_responses[{{ $field->id }}]"
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                                    <option value="">Selecione uma opção</option>
                                                    @foreach($field->options as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        <section class="bg-white rounded-3xl p-8 card-shadow border border-slate-50">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="size-10 rounded-xl bg-slate-50 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">payments</span>
                                </div>
                                <h2 class="text-lg font-black uppercase italic">Método de Pagamento</h2>
                            </div>

                            <div id="payment-methods-wrapper">
                                <div id="payment-selection-container">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                                        <div>
                                            <input checked class="hidden payment-radio" id="credit" name="payment_method"
                                                type="radio" value="credit_card" />
                                            <label
                                                class="flex flex-col items-center justify-center gap-3 p-6 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-primary/30 transition-all"
                                                for="credit">
                                                <span class="material-symbols-outlined text-primary">credit_card</span>
                                                <span class="text-xs font-black uppercase tracking-widest">Cartão de
                                                    Crédito</span>
                                            </label>
                                        </div>
                                        <div>
                                            <input class="hidden payment-radio" id="pix" name="payment_method" type="radio"
                                                value="pix" />
                                            <label
                                                class="flex flex-col items-center justify-center gap-3 p-6 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-primary/30 transition-all"
                                                for="pix">
                                                <span class="material-symbols-outlined text-slate-400">qr_code_2</span>
                                                <span class="text-xs font-black uppercase tracking-widest">Pix</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="credit-card-section" class="space-y-6">
                                        <div class="space-y-1.5">
                                            <label
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome
                                                no Cartão</label>
                                            <input name="cc_holder" id="cc_holder"
                                                class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                placeholder="NOME COMO ESTÁ NO CARTÃO" type="text" />
                                        </div>
                                        <div class="space-y-1.5">
                                            <label
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Número
                                                do Cartão</label>
                                            <div class="relative">
                                                <input name="cc_number" id="cc_number"
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    placeholder="0000 0000 0000 0000" type="text" maxlength="19" />
                                                <div class="absolute right-5 top-1/2 -translate-y-1/2 flex gap-2">
                                                    <span
                                                        class="material-symbols-outlined text-slate-300">credit_card</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-6">
                                            <div class="space-y-1.5">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Validade</label>
                                                <input name="cc_expiry" id="cc_expiry"
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    placeholder="MM/AA" type="text" maxlength="5" />
                                            </div>
                                            <div class="space-y-1.5">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">CVV</label>
                                                <input name="cc_cvv" id="cc_cvv"
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    placeholder="123" type="text" maxlength="4" />
                                            </div>
                                        </div>

                                        <div class="pt-4 border-t border-slate-100">
                                            <p
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">
                                                Endereço de Cobrança</p>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div class="md:col-span-1 space-y-1.5">
                                                    <label
                                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">CEP</label>
                                                    <input name="zip_code" id="zip_code"
                                                        value="{{ auth()->user()->zip_code }}"
                                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                        placeholder="00000-000" type="text" />
                                                </div>
                                                <div class="md:col-span-2 space-y-1.5">
                                                    <label
                                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Endereço</label>
                                                    <input name="address" id="address" value="{{ auth()->user()->address }}"
                                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                        placeholder="Logradouro" type="text" />
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label
                                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Número</label>
                                                    <input name="address_number" id="address_number"
                                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                        placeholder="S/N" type="text" />
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label
                                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Bairro</label>
                                                    <input name="neighborhood" id="neighborhood"
                                                        value="{{ auth()->user()->neighborhood }}"
                                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                        placeholder="Bairro" type="text" />
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label
                                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cidade</label>
                                                    <input name="city" id="city" value="{{ auth()->user()->city }}"
                                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                        placeholder="Cidade" type="text" />
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label
                                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">UF</label>
                                                    <input name="state" id="state" value="{{ auth()->user()->state }}"
                                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all uppercase"
                                                        placeholder="UF" type="text" maxlength="2" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pix-section"
                                        class="hidden mt-6 p-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-center">
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="material-symbols-outlined text-primary text-5xl mb-4">qr_code_2</span>
                                            <p class="text-sm font-bold text-slate-600">O QR Code será gerado após clicar em
                                                finalizar.</p>
                                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mt-2">
                                                Pagamento instantâneo via Pix</p>
                                        </div>
                                    </div>


                                </div>

                                <div id="free-registration-section"
                                    class="hidden py-10 bg-green-50 rounded-2xl border border-dashed border-green-200 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="material-symbols-outlined text-green-600 text-5xl mb-4">redeem</span>
                                        <p class="text-sm font-bold text-green-700">Inscrição 100% Gratuita!</p>
                                        <p class="text-[10px] uppercase font-black tracking-widest text-green-500 mt-2">
                                            Clique em finalizar para garantir sua vaga sem custos.</p>
                                        <input type="hidden" name="payment_method" value="free" id="free-method-input"
                                            disabled>
                                    </div>
                                </div>
                        </section>
                    </form>
                </div>

                <aside class="lg:w-[400px]">
                    <div class="sticky top-32 space-y-6">
                        <div class="bg-white rounded-3xl p-8 card-shadow border border-slate-50 overflow-hidden relative">
                            <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
                            <h2 class="text-xl font-black uppercase italic tracking-tight mb-8">Resumo do <span
                                    class="text-primary">Pedido</span></h2>

                            <div class="flex items-start gap-4 mb-8">
                                <div class="size-16 rounded-2xl bg-slate-100 overflow-hidden flex-shrink-0">
                                    <img alt="{{ $event->name }}" class="w-full h-full object-cover"
                                        src="{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e' }}" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-primary mb-1">
                                        {{ $event->name }}
                                    </p>
                                    <p class="text-sm font-bold leading-tight">Kit {{ $category->name }}</p>
                                    <p class="text-xs text-slate-400 font-medium mt-1 italic">
                                        {{ $event->event_date->translatedFormat('d \d\e F, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4 border-t border-slate-100 pt-8 mb-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span
                                        class="font-medium text-slate-500 uppercase tracking-widest text-[10px]">Inscrição</span>
                                    <span class="font-bold">R$ {{ number_format($category->price, 2, ',', '.') }}</span>
                                </div>
                                <div id="discount-row" class="hidden flex justify-between items-center text-sm">
                                    <span
                                        class="font-medium text-green-600 uppercase tracking-widest text-[10px]">Desconto</span>
                                    <span class="font-bold text-green-600">- R$ <span id="discount-value">0,00</span></span>
                                </div>
                                <div id="fees-breakdown-container" class="space-y-4">
                                    @if(count($feesBreakdown) > 0)
                                        @foreach($feesBreakdown as $fee)
                                            <div class="flex justify-between items-center text-sm">
                                                <span
                                                    class="font-medium text-slate-500 uppercase tracking-widest text-[10px]">{{ $fee['name'] }}</span>
                                                <span class="font-bold text-slate-700">R$
                                                    {{ number_format($fee['amount'], 2, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="font-medium text-slate-500 uppercase tracking-widest text-[10px]">Taxa
                                                de
                                                Serviço</span>
                                            <span class="font-bold text-slate-700">R$ <span
                                                    id="service-fee-value">{{ number_format($serviceFee, 2, ',', '.') }}</span></span>
                                        </div>
                                    @endif
                                </div>
                                <input type="hidden" id="service-fee-init" value="{{ $serviceFee }}">
                            </div>

                            <!-- Cupom de Desconto -->
                            <div class="mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">Cupom
                                    de Desconto</label>
                                <div class="flex gap-2">
                                    <input type="text" id="coupon-code" placeholder="CÓDIGO"
                                        class="flex-1 bg-white border-transparent rounded-xl px-4 py-3 text-xs font-bold uppercase focus:ring-1 focus:ring-primary/30 transition-all shadow-sm">
                                    <button type="button" id="apply-coupon"
                                        class="bg-black text-white px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-sm">
                                        Aplicar
                                    </button>
                                </div>
                                <div id="coupon-message"
                                    class="hidden mt-2 text-[9px] font-bold uppercase tracking-tighter"></div>
                            </div>

                            <div class="flex justify-between items-center mb-10">
                                <span class="text-xs font-black uppercase tracking-widest">Total a pagar</span>
                                <span class="text-3xl font-black italic text-secondary">R$
                                    <span id="final-total">{{ number_format($total, 2, ',', '.') }}</span></span>
                            </div>

                            <button type="submit" form="checkout-form"
                                class="w-full bg-primary text-white py-6 rounded-full text-sm font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                Finalizar Inscrição
                            </button>

                            <div class="mt-6 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-slate-300 text-lg">verified_user</span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ambiente 100%
                                    Criptografado</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-center gap-8 opacity-40 grayscale hover:grayscale-0 transition-all">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-2xl">security</span>
                                <span class="text-[8px] font-black uppercase mt-1">SSL Secure</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-2xl">gpp_maybe</span>
                                <span class="text-[8px] font-black uppercase mt-1">PCI Compliant</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-2xl">encrypted</span>
                                <span class="text-[8px] font-black uppercase mt-1">Safe Pay</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.payment-radio').forEach(radio => {
            radio.addEventListener('change', (e) => {
                const creditSection = document.getElementById('credit-card-section');
                const pixSection = document.getElementById('pix-section');

                // Hide all first
                creditSection.classList.add('hidden');
                pixSection.classList.add('hidden');

                if (e.target.value === 'pix') {
                    pixSection.classList.remove('hidden');
                } else if (e.target.value === 'credit_card') {
                    creditSection.classList.remove('hidden');
                }
            });
        });

        document.getElementById('apply-coupon').addEventListener('click', async function () {
            const code = document.getElementById('coupon-code').value;
            const msg = document.getElementById('coupon-message');
            const btn = this;

            if (!code) return;

            btn.disabled = true;
            btn.innerText = '...';

            try {
                const response = await fetch('{{ route('checkout.coupon.validate', $category->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code })
                });

                const data = await response.json();

                if (data.success) {
                    msg.innerText = 'CUPOM APLICADO COM SUCESSO!';
                    msg.classList.remove('hidden', 'text-red-500');
                    msg.classList.add('text-green-600');

                    document.getElementById('discount-row').classList.remove('hidden');
                    document.getElementById('discount-value').innerText = data.discount.toLocaleString('pt-BR', { minimumFractionDigits: 2 });

                    // Re-render Fees Breakdown
                    const feesContainer = document.getElementById('fees-breakdown-container');
                    if (data.fees_breakdown && data.fees_breakdown.length > 0) {
                        feesContainer.innerHTML = data.fees_breakdown.map(fee => `
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="font-medium text-slate-500 uppercase tracking-widest text-[10px]">${fee.name}</span>
                                            <span class="font-bold text-slate-700">R$ ${fee.amount.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</span>
                                        </div>
                                    `).join('');
                    } else {
                        feesContainer.innerHTML = `
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="font-medium text-slate-500 uppercase tracking-widest text-[10px]">Taxa de Serviço</span>
                                            <span class="font-bold text-slate-700">R$ <span id="service-fee-value">${data.new_service_fee.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</span></span>
                                        </div>
                                    `;
                    }

                    document.getElementById('final-total').innerText = data.new_total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });

                    if (data.new_total <= 0) {
                        document.getElementById('payment-selection-container').classList.add('hidden');
                        document.getElementById('free-registration-section').classList.remove('hidden');
                        document.getElementById('free-method-input').disabled = false;
                        document.getElementById('credit').disabled = true;
                        document.getElementById('pix').disabled = true;
                    }

                    // Prevenir re-aplicação
                    document.getElementById('hidden-coupon-code').value = data.code;
                    document.getElementById('coupon-code').readOnly = true;
                    btn.disabled = true;
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                msg.innerText = error.message || 'ERRO AO APLICAR CUPOM';
                msg.classList.remove('hidden', 'text-green-600');
                msg.classList.add('text-red-500');
                btn.disabled = false;
                btn.innerText = 'Aplicar';
            }
        });

        // Auto-reapply coupon on page load if redirecting back from error
        const initialCoupon = document.getElementById('hidden-coupon-code').value;
        if (initialCoupon) {
            document.getElementById('coupon-code').value = initialCoupon;
            document.getElementById('apply-coupon').click();
        }
    </script>
    <style>
        .payment-radio:checked+label {
            border-color: #0052FF;
            background-color: rgba(0, 82, 255, 0.05);
            box-shadow: 0 0 0 1px #0052FF;
        }

        .payment-radio:checked+label .material-symbols-outlined {
            color: #0052FF;
        }
    </style>

    <script>
        // Máscara de CPF
        const cpfInput = document.getElementById('cpf-input');
        const cpfError = document.getElementById('cpf-error');
        const checkoutForm = document.getElementById('checkout-form');

        // Função para formatar CPF
        function formatCPF(value) {
            value = value.replace(/\D/g, '');

            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }

            return value;
        }

        if (cpfInput) {
            // Formatar CPF ao carregar a página se já houver valor
            if (cpfInput.value) {
                cpfInput.value = formatCPF(cpfInput.value);
            }

            cpfInput.addEventListener('input', function (e) {
                e.target.value = formatCPF(e.target.value);

                // Validar CPF em tempo real
                if (e.target.value.length === 14) {
                    const isValid = validateCPF(e.target.value.replace(/\D/g, ''));
                    if (!isValid) {
                        cpfError.classList.remove('hidden');
                        cpfInput.classList.add('border-red-500');
                    } else {
                        cpfError.classList.add('hidden');
                        cpfInput.classList.remove('border-red-500');
                    }
                }
            });
        }

        // Validação de CPF
        function validateCPF(cpf) {
            cpf = cpf.replace(/\D/g, '');

            if (cpf.length !== 11) return false;

            // Verifica se todos os dígitos são iguais
            if (/^(\d)\1+$/.test(cpf)) return false;

            // Validação do primeiro dígito verificador
            let sum = 0;
            for (let i = 0; i < 9; i++) {
                sum += parseInt(cpf.charAt(i)) * (10 - i);
            }
            let digit = 11 - (sum % 11);
            if (digit >= 10) digit = 0;
            if (digit !== parseInt(cpf.charAt(9))) return false;

            // Validação do segundo dígito verificador
            sum = 0;
            for (let i = 0; i < 10; i++) {
                sum += parseInt(cpf.charAt(i)) * (11 - i);
            }
            digit = 11 - (sum % 11);
            if (digit >= 10) digit = 0;
            if (digit !== parseInt(cpf.charAt(10))) return false;

            return true;
        }

        // Validar antes de submeter
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function (e) {
                const cpfValue = cpfInput.value.replace(/\D/g, '');

                if (!validateCPF(cpfValue)) {
                    e.preventDefault();
                    cpfError.classList.remove('hidden');
                    cpfInput.classList.add('border-red-500');
                    cpfInput.focus();
                    alert('Por favor, insira um CPF válido antes de continuar.');
                    return false;
                }
            });
        }
        // Máscaras Adicionais
        const ccNumber = document.getElementById('cc_number');
        const ccExpiry = document.getElementById('cc_expiry');
        const ccCvv = document.getElementById('cc_cvv');
        const zipCode = document.getElementById('zip_code');

        if (ccNumber) {
            ccNumber.addEventListener('input', (e) => {
                let v = e.target.value.replace(/\D/g, '');
                v = v.replace(/(\d{4})(?=\d)/g, '$1 ');
                e.target.value = v;
            });
        }

        if (ccExpiry) {
            ccExpiry.addEventListener('input', (e) => {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2, 4);
                e.target.value = v;
            });
        }

        if (zipCode) {
            zipCode.addEventListener('input', (e) => {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length > 5) v = v.substring(0, 5) + '-' + v.substring(5, 8);
                e.target.value = v;
            });
        }
    </script>
@endpush