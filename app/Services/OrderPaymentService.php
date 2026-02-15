<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\EmailTemplate;
use App\Mail\DynamicMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Enums\PaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\TicketStatus;

class OrderPaymentService
{
    /**
     * Process order and payment confirmation.
     */
    public function confirmPayment(Order $order, ?Payment $payment, array $paymentData)
    {
        if ($payment && $payment->status === PaymentStatus::Approved) {
            return; // Already processed
        }

        if ($payment) {
            $payment->update([
                'status' => PaymentStatus::Approved,
                'paid_at' => now(),
                'transaction_id' => $paymentData['id'],
            ]);
        }

        $order->update(['status' => OrderStatus::Paid]);

        // Activate Order Items / Tickets
        foreach ($order->items as $item) {
            $item->update(['status' => 'paid']);
            if ($item->ticket) {
                $item->ticket->update(['status' => TicketStatus::Active]);
            } else {
                // Create ticket if not exists (fail-safe)
                $item->ticket()->create([
                    'ticket_number' => 'TKT-' . strtoupper(uniqid()),
                    'status' => TicketStatus::Active,
                ]);
            }
        }

        Log::info("Order #{$order->id} confirmed.");

        // Send Confirmation Email
        $this->sendConfirmationEmail($order);
    }

    /**
     * Send order confirmation email.
     */
    protected function sendConfirmationEmail(Order $order)
    {
        $user = $order->user;
        $template = EmailTemplate::where('slug', 'order_confirmation')->where('is_active', true)->first();

        if ($template && $user) {
            try {
                $firstItem = $order->items->first();
                Mail::to($user->email)->send(new DynamicMail($template, [
                    'nome' => $user->name,
                    'prova' => $firstItem->category->event->name,
                    'inscricao' => $order->order_number,
                    'data' => $firstItem->category->event->event_date->format('d/m/Y'),
                    'link_evento' => route('events.show', $firstItem->category->event->slug),
                ]));
            } catch (\Exception $e) {
                Log::error('OrderPaymentService: Error sending confirmation email: ' . $e->getMessage());
            }
        }
    }
}
