<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\EmailTemplate;
use App\Mail\DynamicMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index(Request $request, Category $category)
    {
        // Se for privado, exige o hash correto no link
        if (!$category->is_public) {
            $kitHash = $request->get('kit');
            if ($kitHash !== $category->access_hash) {
                abort(403, 'Acesso restrito a esta categoria.');
            }
        }

        // Bloqueia se o evento não estiver publicado ou se as inscrições já encerraram ou ainda não começaram
        $isClosed = $category->event->status->value === 'closed';
        $isExpired = $category->event->registration_end
            ? $category->event->registration_end < now()
            : $category->event->event_date < now();
        $notStarted = $category->event->registration_start && $category->event->registration_start > now();

        if ($category->event->status !== \App\Enums\EventStatus::Published || $isExpired || $notStarted) {
            $statusLabel = 'indisponíveis';

            if ($isExpired || $isClosed) {
                $statusLabel = 'encerradas';
            } elseif ($notStarted) {
                $statusLabel = 'ainda não iniciadas';
            } elseif ($category->event->status->value === 'cancelled') {
                $statusLabel = 'canceladas';
            }

            return redirect()->route('events.show', $category->event->slug)
                ->with('error', "Desculpe, as inscrições para este evento estão {$statusLabel}.");
        }

        // Verifica se ainda há vagas
        if ($category->available_tickets <= 0) {
            return redirect()->route('events.show', $category->event->slug)
                ->with('error', 'Desculpe, as vagas para este kit acabaram de se esgotar!');
        }
        $event = $category->event->load('customFields');

        $serviceFee = 0;
        $feesBreakdown = [];

        if (!$event->ignore_fees) {
            $activeFees = \App\Models\ServiceFee::where('is_active', true)->get();
            foreach ($activeFees as $fee) {
                $amount = 0;
                if ($fee->type === 'fixed') {
                    $amount = $fee->value;
                } else {
                    $amount = ($category->price * ($fee->value / 100));
                }

                if ($amount > 0) {
                    $serviceFee += $amount;
                    $feesBreakdown[] = [
                        'name' => $fee->name,
                        'amount' => $amount
                    ];
                }
            }
        }

        $total = $category->price + $serviceFee;

        return view('checkout.index', compact('category', 'event', 'serviceFee', 'total', 'feesBreakdown'));
    }

    public function process(Request $request, Category $category, \App\Services\AsaasService $asaasService)
    {
        $user = auth()->user();

        // Calcular total primeiro para validação condicional
        $originalPrice = $category->price;
        $discountValue = 0;

        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\EventCoupon::where('event_id', $category->event_id)
                ->where('code', strtoupper($request->coupon_code))
                ->first();

            if ($coupon && $coupon->isValid()) {
                $discountValue = $coupon->calculateDiscount($originalPrice);
            }
        }

        $finalPrice = max(0, $originalPrice - $discountValue);

        $feesAmount = 0;
        if (!$category->event->ignore_fees) {
            $activeFees = \App\Models\ServiceFee::where('is_active', true)->get();
            foreach ($activeFees as $fee) {
                if ($fee->type === 'fixed') {
                    $feesAmount += $fee->value;
                } else {
                    $feesAmount += ($finalPrice * ($fee->value / 100));
                }
            }
        }

        $total = $finalPrice + $feesAmount;

        // Validação condicional
        $rules = [
            'cpf' => 'required|string|size:14', // Formato: 000.000.000-00
        ];

        // Validação de dados cadastrais faltantes
        if (!$user->birth_date) {
            $rules['birth_date'] = 'required|date|before:today';
        }
        if (!$user->gender) {
            $rules['gender'] = 'required|in:M,F';
        }

        // Só exigir payment_method se o total for maior que 0
        if ($total > 0) {
            $rules['payment_method'] = 'required|in:pix,boleto,credit_card';
        }

        $request->validate($rules);

        // Atualizar dados do usuário se vierem na request
        $userDataToUpdate = [];
        if (!$user->birth_date && $request->filled('birth_date')) {
            $userDataToUpdate['birth_date'] = $request->birth_date;
        }
        if (!$user->gender && $request->filled('gender')) {
            $userDataToUpdate['gender'] = $request->gender;
        }

        if (!empty($userDataToUpdate)) {
            $user->update($userDataToUpdate);
        }

        // Limpar e validar CPF
        $cpfClean = preg_replace('/\D/', '', $request->cpf);

        if (!$this->isValidCPF($cpfClean)) {
            return back()->withErrors(['cpf' => 'CPF inválido. Por favor, insira um CPF válido.'])->withInput();
        }

        // Check if CPF already has an ACTIVE (paid) registration for this specific event
        $existingRegistration = \App\Models\Order::whereHas('items', function ($query) use ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('event_id', $category->event_id);
            });
        })
            ->whereHas('user', function ($query) use ($cpfClean) {
                $query->where('cpf', $cpfClean);
            })
            ->where('status', \App\Enums\OrderStatus::Paid) // ONLY block if the order is actually Paid/Active
            ->first();

        if ($existingRegistration) {
            return back()->withErrors(['cpf' => 'Este CPF já possui uma inscrição confirmada (paga) neste evento.'])->withInput();
        }

        // Verifica se o CPF já pertence a outro usuário
        $exists = \App\Models\User::where('cpf', $cpfClean)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['cpf' => 'Este CPF já está vinculado a outro usuário.'])->withInput();
        }

        // Atualizar CPF do usuário se diferente
        if ($user->cpf !== $cpfClean) {
            $user->update(['cpf' => $cpfClean]);
        }

        try {
            return \DB::transaction(function () use ($request, $category, $user, $asaasService, $cpfClean) {
                // Reload category with lock to prevent race conditions
                $category = Category::where('id', $category->id)->lockForUpdate()->first();

                $isExpired = $category->event->registration_end
                    ? $category->event->registration_end < now()
                    : $category->event->event_date < now();
                $notStarted = $category->event->registration_start && $category->event->registration_start > now();

                if ($category->event->status !== \App\Enums\EventStatus::Published || $isExpired || $notStarted) {
                    return redirect()->route('events.show', $category->event->slug)
                        ->with('error', 'Infelizmente as inscrições para este evento não estão disponíveis no momento.');
                }

                if ($category->available_tickets <= 0) {
                    return redirect()->route('events.show', $category->event->slug)
                        ->with('error', 'Infelizmente as vagas para este kit acabaram de esgotar.');
                }

                // Calculate Discount first
                $originalPrice = $category->price;
                $discountValue = 0;
                $couponId = null;

                \Log::info('Coupon Processing DEBUG', [
                    'received_coupon_code' => $request->coupon_code,
                    'is_filled' => $request->filled('coupon_code')
                ]);

                if ($request->filled('coupon_code')) {
                    $coupon = \App\Models\EventCoupon::where('event_id', $category->event_id)
                        ->where('code', strtoupper($request->coupon_code))
                        ->first();

                    if ($coupon && $coupon->isValid()) {
                        $discountValue = $coupon->calculateDiscount($originalPrice);
                        $couponId = $coupon->id;
                        $coupon->increment('used_count');
                        \Log::info('Coupon Applied Successfully', ['code' => $coupon->code, 'discount' => $discountValue]);
                    } else {
                        \Log::warning('Coupon Found but Invalid or Not Found', ['code' => $request->coupon_code]);
                    }
                }

                $finalPrice = round($originalPrice - $discountValue, 2);

                \Log::info('Price Calculation DEBUG', [
                    'originalPrice' => $originalPrice,
                    'discountValue' => $discountValue,
                    'finalPrice' => $finalPrice
                ]);

                $feesAmount = 0;
                if (!$category->event->ignore_fees) {
                    $activeFees = \App\Models\ServiceFee::where('is_active', true)->get();
                    foreach ($activeFees as $fee) {
                        $amount = 0;
                        if ($fee->type === 'fixed') {
                            $amount = $fee->value;
                        } else {
                            $amount = ($finalPrice * ($fee->value / 100));
                        }
                        $feesAmount += $amount;
                    }
                }

                $feesAmount = round($feesAmount, 2);
                $total = round($finalPrice + $feesAmount, 2);

                // Create Order
                $order = \App\Models\Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'total_amount' => $total,
                    'fees_amount' => $feesAmount,
                    'status' => $total > 0 ? \App\Enums\OrderStatus::Pending : \App\Enums\OrderStatus::Paid,
                    'payment_method' => $request->payment_method,
                ]);

                // Create Order Item (Subscription)
                $orderItem = $order->items()->create([
                    'category_id' => $category->id,
                    'participant_name' => $user->name,
                    'participant_cpf' => $cpfClean,
                    'participant_email' => $user->email,
                    'participant_birth_date' => $user->birth_date,
                    'price' => $finalPrice,
                    'status' => $total > 0 ? \App\Enums\OrderStatus::Pending : \App\Enums\OrderStatus::Paid,
                    'custom_responses' => $request->custom_responses,
                ]);

                $paymentData = null;
                $pixQrCode = null;
                $pixQrCodeBase64 = null;

                // Handle Asaas Payment

                if ($total > 0) {
                    try {
                        // Determine Billing Type for Asaas
                        $billingType = match ($request->payment_method) {
                            'pix' => 'PIX',
                            'boleto' => 'BOLETO',
                            'credit_card' => 'CREDIT_CARD',
                            default => 'PIX',
                        };

                        $creditCardInfo = null;
                        if ($request->payment_method === 'credit_card') {
                            $request->validate([
                                'cc_holder' => 'required|string',
                                'cc_number' => 'required|string',
                                'cc_expiry' => 'required|string',
                                'cc_cvv' => 'required|string',
                                'zip_code' => 'required|string',
                                'address' => 'required|string',
                                'address_number' => 'required|string',
                                'neighborhood' => 'required|string',
                                'city' => 'required|string',
                                'state' => 'required|string',
                            ]);

                            // Parse Expiry
                            $expiry = explode('/', $request->cc_expiry);
                            if (count($expiry) !== 2) {
                                throw new \Exception('Data de validade do cartão inválida.');
                            }

                            $creditCardInfo = [
                                'holderName' => $request->cc_holder,
                                'number' => preg_replace('/\D/', '', $request->cc_number),
                                'expiryMonth' => trim($expiry[0]),
                                'expiryYear' => '20' . trim($expiry[1]),
                                'ccv' => $request->cc_cvv,
                                'addressInfo' => [
                                    'zip_code' => $request->zip_code,
                                    'address' => $request->address,
                                    'address_number' => $request->address_number,
                                    'neighborhood' => $request->neighborhood,
                                    'city' => $request->city,
                                    'state' => $request->state,
                                ]
                            ];

                            // Atualizar dados de endereço do usuário
                            $user->update([
                                'zip_code' => $request->zip_code,
                                'address' => $request->address,
                                'neighborhood' => $request->neighborhood,
                                'city' => $request->city,
                                'state' => $request->state,
                            ]);
                        }

                        $paymentResponse = $asaasService->createPayment($order, $billingType, $creditCardInfo);

                        // Validar resposta do pagamento
                        if (!isset($paymentResponse['id'])) {
                            \Log::error('Invalid Asaas Payment Response', [
                                'order_id' => $order->id,
                                'response' => $paymentResponse
                            ]);
                            throw new \Exception('Resposta inválida da API Asaas ao criar pagamento');
                        }

                        $paymentStatus = \App\Enums\PaymentStatus::Pending;
                        if ($request->payment_method === 'credit_card') {
                            if ($paymentResponse['status'] === 'CONFIRMED' || $paymentResponse['status'] === 'RECEIVED') {
                                $paymentStatus = \App\Enums\PaymentStatus::Approved;
                            } elseif (in_array($paymentResponse['status'], ['OVERDUE', 'REFUNDED', 'DELETED'])) {
                                $paymentStatus = \App\Enums\PaymentStatus::Rejected;
                            }
                        }

                        $paymentData = [
                            'payment_gateway' => 'asaas',
                            'asaas_payment_id' => $paymentResponse['id'],
                            'invoice_url' => $paymentResponse['invoiceUrl'] ?? null,
                            'amount' => $total,
                            'status' => $paymentStatus,
                            'payment_method' => $request->payment_method,
                        ];

                        if ($paymentStatus === \App\Enums\PaymentStatus::Approved) {
                            $order->update(['status' => \App\Enums\OrderStatus::Paid]);
                            $order->items()->update(['status' => \App\Enums\OrderStatus::Paid]);
                        }

                        if ($billingType === 'PIX') {
                            $pixData = $asaasService->getPixQrCode($paymentResponse['id']);
                            \Log::info('Pix QR Code Data Retrieved', ['pix_data' => $pixData]);
                            if ($pixData && isset($pixData['payload'])) {
                                $pixQrCode = $pixData['payload'];
                                $pixQrCodeBase64 = $pixData['encodedImage'];
                                \Log::info('Pix QR Code Assigned to Variables', [
                                    'qr_code_length' => strlen($pixQrCode),
                                    'base64_length' => strlen($pixQrCodeBase64)
                                ]);
                            } else {
                                \Log::warning('Pix QR Code is null or missing payload - payment may not be ready yet');
                            }
                        }

                    } catch (\Exception $e) {
                        // Log error and rollback
                        \Log::error('Checkout Payment Error: ' . $e->getMessage());
                        throw $e;
                    }
                } else {
                    // Free Event
                    $paymentData = [
                        'payment_gateway' => 'free',
                        'amount' => 0,
                        'status' => \App\Enums\PaymentStatus::Approved,
                        'payment_method' => 'free',
                        'paid_at' => now(),
                    ];

                    // Active Ticket immediately

                }

                // Create Payment Record
                \Log::info('About to create payment record', [
                    'paymentData' => $paymentData,
                    'pixQrCode' => $pixQrCode ? substr($pixQrCode, 0, 50) . '...' : 'NULL',
                    'pixQrCodeBase64' => $pixQrCodeBase64 ? 'EXISTS (length: ' . strlen($pixQrCodeBase64) . ')' : 'NULL'
                ]);
                $paymentData['pix_qr_code'] = $pixQrCode;
                $paymentData['pix_qr_code_base64'] = $pixQrCodeBase64;
                $payment = $order->payments()->create($paymentData);


                \Log::info('Payment record created', [
                    'payment_id' => $payment->id,
                    'has_pix_qr_code' => !empty($payment->pix_qr_code),
                    'has_pix_qr_code_base64' => !empty($payment->pix_qr_code_base64)
                ]);

                // Create Ticket (Subscription Badge) - Always create, status depends on payment
                $orderItem->ticket()->create([
                    'ticket_number' => 'TKT-' . strtoupper(uniqid()),
                    'status' => $total > 0 ? \App\Enums\TicketStatus::Pending : \App\Enums\TicketStatus::Active,
                ]);


                $successMessage = $total > 0
                    ? 'Inscrição realizada! Finalize o pagamento para confirmar sua vaga.'
                    : 'Inscrição confirmada! Sua vaga está garantida.';

                // Enviar E-mail de Confirmação se for gratuito (Pago é via Webhook ou finalização manual se quisermos)
                // O USER especificou "quando a pessoa compra uma corrida, falando que foi inscrito"
                // Se for gratuito, enviamos agora. Se for pago, talvez devêssemos enviar na aprovação,
                // mas o controlador atual redireciona para confirmação. Vou enviar aqui para gratúitos e marcar para enviar no pagamento.
                if ($total == 0) {
                    $template = EmailTemplate::where('slug', 'order_confirmation')->where('is_active', true)->first();
                    if ($template) {
                        try {
                            Mail::to($user->email)->send(new DynamicMail($template, [
                                'nome' => $user->name,
                                'prova' => $category->event->name,
                                'inscricao' => $order->order_number,
                                'data' => $category->event->event_date->format('d/m/Y'),
                                'link_evento' => route('events.show', $category->event->slug),
                            ]));
                        } catch (\Exception $e) {
                            \Log::error('Erro ao enviar e-mail de confirmação: ' . $e->getMessage());
                        }
                    }
                }

                return redirect()->route('checkout.confirmation', $order->id)->with('success', $successMessage);
            });
        } catch (\Exception $e) {
            \Log::error('Checkout Process Error: ' . $e->getMessage());
            return back()->withErrors(['payment' => $e->getMessage()])->withInput();
        }
    }

    public function checkPaymentStatus(\App\Models\Order $order)
    {
        // Ensure the order belongs to the user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        /** @var \App\Models\Order $order */
        /** @var \App\Models\Payment $payment */
        $payment = $order->payments()->latest()->first();
        $isPaid = $order->status === \App\Enums\OrderStatus::Paid ||
            ($payment && $payment->status === \App\Enums\PaymentStatus::Approved);

        // Se ainda não estiver pago no banco local, verifica no Asaas (apenas se tiver um pagamento asaas salvo)
        if (!$isPaid && $payment && $payment->asaas_payment_id) {
            try {
                $asaasService = app(\App\Services\AsaasService::class);
                $asaasPayment = $asaasService->getPayment($payment->asaas_payment_id);

                if ($asaasPayment && in_array($asaasPayment['status'], ['CONFIRMED', 'RECEIVED'])) {
                    // Confirma o pagamento localmente usando o serviço centralizado
                    app(\App\Services\OrderPaymentService::class)->confirmPayment($order, $payment, $asaasPayment);
                    $isPaid = true;
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao verificar status direto no Asaas: ' . $e->getMessage());
            }
        }

        return response()->json([
            'order_status' => $order->refresh()->status->value,
            'payment_status' => $payment ? $payment->refresh()->status->value : null,
            'is_paid' => $isPaid,
        ]);
    }

    public function confirmation(\App\Models\Order $order)
    {
        // Ensure the order belongs to the user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.category.event', 'items.ticket']);
        $serviceFee = $order->fees_amount;
        $payment = $order->payments()->first();
        return view('checkout.confirmation', compact('order', 'serviceFee', 'payment'));
    }

    public function validateCoupon(Request $request, Category $category)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = \App\Models\EventCoupon::where('event_id', $category->event_id)
            ->where('code', strtoupper($request->code))
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Cupom inválido.'], 422);
        }

        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'Cupom inspirado ou limite de uso atingido.'], 422);
        }

        $discount = $coupon->calculateDiscount($category->price);
        $newPrice = $category->price - $discount;

        $newFeesAmount = 0;
        $feesBreakdown = [];
        if (!$category->event->ignore_fees) {
            $activeFees = \App\Models\ServiceFee::where('is_active', true)->get();
            foreach ($activeFees as $fee) {
                $amount = 0;
                if ($fee->type === 'fixed') {
                    $amount = $fee->value;
                } else {
                    $amount = ($newPrice * ($fee->value / 100));
                }

                if ($amount > 0) {
                    $newFeesAmount += $amount;
                    $feesBreakdown[] = [
                        'name' => $fee->name,
                        'amount' => $amount
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount' => $discount,
            'new_service_fee' => $newFeesAmount,
            'new_total' => $newPrice + $newFeesAmount,
            'fees_breakdown' => $feesBreakdown,
        ]);
    }

    /**
     * Validate Brazilian CPF
     */
    private function isValidCPF($cpf)
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        // Check if all digits are the same
        if (preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }

        // Validate first digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($cpf[$i]) * (10 - $i);
        }
        $digit = 11 - ($sum % 11);
        if ($digit >= 10)
            $digit = 0;
        if ($digit != intval($cpf[9]))
            return false;

        // Validate second digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += intval($cpf[$i]) * (11 - $i);
        }
        $digit = 11 - ($sum % 11);
        if ($digit >= 10)
            $digit = 0;
        if ($digit != intval($cpf[10]))
            return false;

        return true;
    }
}
