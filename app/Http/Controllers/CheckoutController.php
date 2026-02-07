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

        // Bloqueia se o evento não estiver publicado ou se as inscrições já encerraram
        $isClosed = $category->event->status->value === 'closed';
        $isExpired = $category->event->registration_end
            ? $category->event->registration_end < now()
            : $category->event->event_date < now();

        if ($category->event->status !== \App\Enums\EventStatus::Published || $isExpired) {
            $statusLabel = 'indisponíveis';

            if ($isExpired || $isClosed) {
                $statusLabel = 'encerradas';
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
        $serviceFee = $category->price > 0 ? ($category->price * 0.07) : 0;
        $total = $category->price + $serviceFee;

        return view('checkout.index', compact('category', 'event', 'serviceFee', 'total'));
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
        $serviceFee = $finalPrice > 0 ? ($finalPrice * 0.07) : 0;
        $total = $finalPrice + $serviceFee;

        // Validação condicional
        $rules = [
            'cpf' => 'required|string|size:14', // Formato: 000.000.000-00
        ];

        // Só exigir payment_method se o total for maior que 0
        if ($total > 0) {
            $rules['payment_method'] = 'required|in:pix,boleto,credit_card';
        }

        $request->validate($rules);

        // Limpar e validar CPF
        $cpf = preg_replace('/\D/', '', $request->cpf);

        if (!$this->isValidCPF($cpf)) {
            return back()->withErrors(['cpf' => 'CPF inválido. Por favor, insira um CPF válido.'])->withInput();
        }

        // Atualizar CPF do usuário se diferente
        if ($user->cpf !== $cpf) {
            $user->update(['cpf' => $cpf]);
        }

        return \DB::transaction(function () use ($request, $category, $user, $asaasService, $cpf) {
            // Reload category with lock to prevent race conditions
            $category = Category::where('id', $category->id)->lockForUpdate()->first();

            $isExpired = $category->event->registration_end
                ? $category->event->registration_end < now()
                : $category->event->event_date < now();

            if ($category->event->status !== \App\Enums\EventStatus::Published || $isExpired) {
                return redirect()->route('events.show', $category->event->slug)
                    ->with('error', 'Infelizmente as inscrições para este evento não estão mais disponíveis.');
            }

            if ($category->available_tickets <= 0) {
                return redirect()->route('events.show', $category->event->slug)
                    ->with('error', 'Infelizmente as vagas para este kit acabaram de esgotar.');
            }

            // Calculate Discount first
            $originalPrice = $category->price;
            $discountValue = 0;
            $couponId = null;

            if ($request->filled('coupon_code')) {
                $coupon = \App\Models\EventCoupon::where('event_id', $category->event_id)
                    ->where('code', strtoupper($request->coupon_code))
                    ->first();

                if ($coupon && $coupon->isValid()) {
                    $discountValue = $coupon->calculateDiscount($originalPrice);
                    $couponId = $coupon->id;
                    $coupon->increment('used_count');
                }
            }

            $finalPrice = $originalPrice - $discountValue;
            $serviceFee = $finalPrice > 0 ? ($finalPrice * 0.07) : 0;
            $total = $finalPrice + $serviceFee;

            // Create Order
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $total,
                'status' => $total > 0 ? \App\Enums\OrderStatus::Pending : \App\Enums\OrderStatus::Paid,
                'payment_method' => $request->payment_method,
            ]);

            // Create Order Item (Subscription)
            $orderItem = $order->items()->create([
                'category_id' => $category->id,
                'participant_name' => $user->name,
                'participant_cpf' => $cpf,
                'participant_email' => $user->email,
                'participant_birth_date' => $user->birth_date ?? now()->subYears(20),
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

                    // Allow generic billing link if user chooses "Outros" or implement specific logic.
                    // For this iteration: 
                    // PIX -> Get QR Code
                    // BOLETO -> Get Barcode/Link
                    // CREDIT_CARD -> We need card data OR just give them the Invoice Link (Payment Link).
                    // Asaas API "Create Payment" creates a charge. The response has "invoiceUrl".

                    $paymentResponse = $asaasService->createPayment($order, $billingType);

                    // Validar resposta do pagamento
                    if (!isset($paymentResponse['id'])) {
                        \Log::error('Invalid Asaas Payment Response', [
                            'order_id' => $order->id,
                            'response' => $paymentResponse
                        ]);
                        throw new \Exception('Resposta inválida da API Asaas ao criar pagamento');
                    }

                    $paymentData = [
                        'payment_gateway' => 'asaas',
                        'asaas_payment_id' => $paymentResponse['id'],
                        'invoice_url' => $paymentResponse['invoiceUrl'] ?? null,
                        'amount' => $total,
                        'status' => \App\Enums\PaymentStatus::Pending,
                        'payment_method' => $request->payment_method,

                    ];

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
                    // Log error and redirect back
                    \Log::error('Checkout Payment Error: ' . $e->getMessage());
                    throw $e; // Rollback transaction
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
    }

    public function checkPaymentStatus(\App\Models\Order $order)
    {
        // Ensure the order belongs to the user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $payment = $order->payments()->latest()->first();

        return response()->json([
            'order_status' => $order->status->value,
            'payment_status' => $payment ? $payment->status->value : null,
            'is_paid' => $order->status === \App\Enums\OrderStatus::Paid ||
                ($payment && $payment->status === \App\Enums\PaymentStatus::Approved),
        ]);
    }

    public function confirmation(\App\Models\Order $order)
    {
        // Ensure the order belongs to the user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.category.event', 'items.ticket']);
        $serviceFee = $order->total_amount - $order->items->sum('price');
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
        $newServiceFee = $newPrice > 0 ? ($newPrice * 0.07) : 0;

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount' => $discount,
            'new_service_fee' => $newServiceFee,
            'new_total' => $newPrice + $newServiceFee,
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
