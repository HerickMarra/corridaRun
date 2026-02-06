<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleAsaas(Request $request)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? null;
        $paymentData = $payload['payment'] ?? null;

        // Salvar log do webhook
        $webhookLog = WebhookLog::create([
            'event' => $event ?? 'unknown',
            'payload' => $payload,
            'payment_id' => $paymentData['id'] ?? null,
            'order_id' => $paymentData['externalReference'] ?? null,
            'status_code' => 200,
            'processed_at' => now(),
        ]);

        Log::info('Asaas Webhook Received', ['event' => $event, 'payment_id' => $paymentData['id'] ?? null]);

        if (!$event || !$paymentData || !isset($paymentData['externalReference'])) {
            $webhookLog->update(['status_code' => 200]); // Ignored but successful
            return response()->json(['status' => 'ignored'], 200);
        }

        $orderId = $paymentData['externalReference'];
        $order = Order::find($orderId);

        if (!$order) {
            $webhookLog->update(['status_code' => 404]);
            Log::warning('Asaas Webhook: Order not found', ['order_id' => $orderId]);
            return response()->json(['status' => 'order_not_found'], 404);
        }

        $payment = Payment::where('asaas_payment_id', $paymentData['id'])->first();

        // If payment record not found by Asaas ID, try finding by Order ID (if it was created pending)
        if (!$payment) {
            $payment = $order->payments()->first();
            if ($payment && !$payment->asaas_payment_id) {
                $payment->update(['asaas_payment_id' => $paymentData['id']]);
            }
        }

        switch ($event) {
            case 'PAYMENT_CONFIRMED':
            case 'PAYMENT_RECEIVED':
                $this->handlePaymentConfirmed($order, $payment, $paymentData);
                break;

            case 'PAYMENT_OVERDUE':
            case 'PAYMENT_REFUNDED':
                $this->handlePaymentFailed($order, $payment, $paymentData);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentConfirmed(Order $order, ?Payment $payment, array $paymentData)
    {
        if ($payment) {
            $payment->update([
                'status' => \App\Enums\PaymentStatus::Approved,
                'paid_at' => now(),
                'transaction_id' => $paymentData['id'],
            ]);
        }

        $order->update(['status' => \App\Enums\OrderStatus::Paid]);

        // Activate Order Items / Tickets
        foreach ($order->items as $item) {
            $item->update(['status' => 'paid']);
            if ($item->ticket) {
                $item->ticket->update(['status' => \App\Enums\TicketStatus::Active]);
            } else {
                // Create ticket if not exists (fail-safe)
                $item->ticket()->create([
                    'ticket_number' => 'TKT-' . strtoupper(uniqid()),
                    'status' => \App\Enums\TicketStatus::Active,
                ]);
            }
        }

        Log::info("Order #{$order->id} confirmed via Asaas Webhook.");
    }

    protected function handlePaymentFailed(Order $order, ?Payment $payment, array $paymentData)
    {
        if ($payment) {
            $payment->update(['status' => \App\Enums\PaymentStatus::Rejected]); // Or Cancelled
        }

        //$order->update(['status' => \App\Enums\OrderStatus::Cancelled]); // Optional: Determine if overdue cancels immediately
        Log::info("Order #{$order->id} payment failed/overdue/refunded via Asaas Webhook.");
    }
}
