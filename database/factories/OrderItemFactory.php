<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'category_id' => Category::factory(),
            'participant_name' => fake()->name(),
            'participant_cpf' => fake()->numerify('###.###.###-##'),
            'participant_email' => fake()->email(),
            'participant_birth_date' => fake()->date('Y-m-d', '-18 years'),
            'price' => 0,
            'status' => 'paid',
        ];
    }
}
