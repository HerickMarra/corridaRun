<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(fake()->unique()->bothify('??#?#?#?')),
            'total_amount' => 0,
            'status' => fake()->randomElement(OrderStatus::cases()),
            'payment_method' => fake()->randomElement(['credit_card', 'pix', 'boleto']),
        ];
    }
}
