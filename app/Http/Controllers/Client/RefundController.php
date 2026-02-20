<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    protected $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    /**
     * Request a refund for a payment.
     */
    public function store(Request $request, Payment $payment)
    {
        $user = auth()->user();
        $order = $payment->order;
        $event = $order->items->first()->category->event;

        // 1. Authorization
        if ($user->id !== $order->user_id && !$user->role->isAdmin()) {
            abort(403, 'Você não tem permissão para solicitar este reembolso.');
        }

        // 2. Rules for Users (Non-Admins)
        if (!$user->role->isAdmin()) {
            // Check if event allows refunds
            if (!$event->allow_user_refund) {
                return back()->with('error', 'Este evento não permite solicitação de reembolso pelo usuário.');
            }

            // Check 7 days rule
            if ($payment->paid_at->diffInDays(now()) > 7) {
                return back()->with('error', 'O prazo de 7 dias para arrependimento já expirou.');
            }

            // Check 48h before event rule
            if ($event->event_date->subHours(48)->isPast()) {
                return back()->with('error', 'O reembolso só pode ser solicitado com até 48h de antecedência do evento.');
            }
        }

        // 3. Process Refund
        try {
            $description = $user->role->isAdmin()
                ? "Extorno solicitado pelo Admin ({$user->name})"
                : "Cancelamento solicitado pelo cliente ({$user->name})";

            $asaasRefund = $this->asaasService->refundPayment(
                $payment->payment_gateway_id ?? $payment->transaction_id,
                $payment->amount,
                $description
            );

            // 4. Update Database
            $payment->update([
                'refunded_at' => now(),
                'refund_amount' => $payment->amount,
                'refund_status' => 'processed',
                'status' => 'refunded'
            ]);

            $order->update(['status' => \App\Enums\OrderStatus::Refunded]);

            // Cancel associated tickets
            foreach ($order->items as $item) {
                $item->tickets()->delete(); // Soft delete tickets
            }

            return back()->with('success', 'Reembolso realizado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Refund Error', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao processar reembolso: ' . $e->getMessage());
        }
    }
}
