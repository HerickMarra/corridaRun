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
                        <input type="hidden" name="coupon_code" id="hidden-coupon-code">
                        <section class="bg-white rounded-3xl p-8 card-shadow border border-slate-50 mb-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="size-10 rounded-xl bg-slate-50 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <h2 class="text-lg font-black uppercase italic">Identificação do Atleta</h2>
                            </div>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome
                                        Completo</label>
                                    <input
                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all disabled:opacity-70"
                                        readonly type="text" value="{{ auth()->user()->name }}" />
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">E-mail</label>
                                    <input
                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all disabled:opacity-70"
                                        readonly type="email" value="{{ auth()->user()->email }}" />
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">CPF</label>
                                    <input name="cpf"
                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                        placeholder="000.000.000-00" type="text" value="{{ auth()->user()->cpf }}" />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Data
                                        de Nascimento</label>
                                    <input name="birth_date"
                                        class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                        placeholder="DD/MM/AAAA" type="text"
                                        value="{{ auth()->user()->birth_date && auth()->user()->birth_date instanceof \Carbon\Carbon ? auth()->user()->birth_date->format('d/m/Y') : '' }}" />
                                </div>
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
                                        <div>
                                            <input class="hidden payment-radio" id="boleto" name="payment_method"
                                                type="radio" value="boleto" />
                                            <label
                                                class="flex flex-col items-center justify-center gap-3 p-6 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-primary/30 transition-all"
                                                for="boleto">
                                                <span class="material-symbols-outlined text-slate-400">barcode</span>
                                                <span class="text-xs font-black uppercase tracking-widest">Boleto</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="credit-card-section" class="space-y-6">
                                        <div class="space-y-1.5">
                                            <label
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Número
                                                do Cartão</label>
                                            <div class="relative">
                                                <input
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    placeholder="0000 0000 0000 0000" type="text" />
                                                <div class="absolute right-5 top-1/2 -translate-y-1/2 flex gap-2">
                                                    <div class="h-6 w-10 bg-slate-200 rounded-md"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-6">
                                            <div class="space-y-1.5">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Validade</label>
                                                <input
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    placeholder="MM/AA" type="text" />
                                            </div>
                                            <div class="space-y-1.5">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">CVV</label>
                                                <input
                                                    class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white transition-all"
                                                    placeholder="123" type="text" />
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

                                    <div id="boleto-section"
                                        class="hidden mt-6 p-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-center">
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="material-symbols-outlined text-primary text-5xl mb-4">barcode</span>
                                            <p class="text-sm font-bold text-slate-600">O Boleto será gerado após clicar em
                                                finalizar.</p>
                                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mt-2">
                                                Pode levar até 3 dias úteis para compensar</p>
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
                                <div class="flex justify-between items-center text-sm">
                                    <span class="font-medium text-slate-500 uppercase tracking-widest text-[10px]">Taxa de
                                        Serviço (7%)</span>
                                    <span class="font-bold">R$ <span
                                            id="service-fee-value">{{ number_format($serviceFee, 2, ',', '.') }}</span></span>
                                </div>
                            </div>

                            <!-- Cupom de Desconto -->
                            <div class="mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">Cupom
                                    de Desconto</label>
                                <div class="flex gap-2">
                                    <input type="text" id="coupon-code" name="coupon_code" placeholder="CÓDIGO"
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
                const boletoSection = document.getElementById('boleto-section');

                // Hide all first
                creditSection.classList.add('hidden');
                pixSection.classList.add('hidden');
                if (boletoSection) boletoSection.classList.add('hidden');

                if (e.target.value === 'pix') {
                    pixSection.classList.remove('hidden');
                } else if (e.target.value === 'boleto') {
                    if (boletoSection) boletoSection.classList.remove('hidden');
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
                    document.getElementById('service-fee-value').innerText = data.new_service_fee.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
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
@endpush