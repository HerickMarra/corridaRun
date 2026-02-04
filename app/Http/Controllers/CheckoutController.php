<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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

        // Bloqueia se o evento não estiver publicado
        if ($category->event->status !== \App\Enums\EventStatus::Published) {
            $statusLabel = match ($category->event->status->value) {
                'closed' => 'encerradas',
                'cancelled' => 'canceladas',
                'draft' => 'indisponíveis',
                default => 'indisponíveis'
            };
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

    public function process(Request $request, Category $category)
    {
        $user = auth()->user();

        return \DB::transaction(function () use ($request, $category, $user) {
            // Reload category with lock to prevent race conditions
            $category = Category::where('id', $category->id)->lockForUpdate()->first();

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
                'status' => \App\Enums\OrderStatus::Paid,
                'payment_method' => $total <= 0 ? 'free' : ($request->payment_method ?? 'credit_card'),
            ]);

            // Create Order Item (Subscription)
            $orderItem = $order->items()->create([
                'category_id' => $category->id,
                'participant_name' => $user->name,
                'participant_cpf' => $request->cpf ?? $user->cpf ?? '000.000.000-00',
                'participant_email' => $user->email,
                'participant_birth_date' => $user->birth_date ?? now()->subYears(20),
                'price' => $finalPrice,
                'status' => 'paid',
                'custom_responses' => $request->custom_responses,
            ]);

            // Create Payment
            $order->payments()->create([
                'payment_gateway' => 'fake',
                'amount' => $total,
                'status' => \App\Enums\PaymentStatus::Approved,
                'payment_method' => $request->payment_method,
                'paid_at' => now(),
            ]);

            // Create Ticket (Subscription Badge)
            $orderItem->ticket()->create([
                'ticket_number' => 'TKT-' . strtoupper(uniqid()),
                'status' => \App\Enums\TicketStatus::Active,
            ]);

            return redirect()->route('checkout.confirmation', $order->id)->with('success', 'Inscrição confirmada com sucesso! Bem-vindo à prova.');
        });
    }

    public function confirmation(\App\Models\Order $order)
    {
        // Ensure the order belongs to the user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.category.event', 'items.ticket']);
        $serviceFee = $order->total_amount - $order->items->sum('price');

        return view('checkout.confirmation', compact('order', 'serviceFee'));
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
}
