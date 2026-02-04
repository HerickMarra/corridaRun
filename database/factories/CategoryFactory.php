<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $distance = fake()->randomElement(['5km', '10km', '21km', '42km']);
        return [
            'name' => $distance . ' ' . fake()->randomElement(['Individual', 'Elite', 'Amador']),
            'distance' => $distance,
            'price' => fake()->randomFloat(2, 60, 250),
            'max_participants' => fake()->randomElement([100, 200, 500]),
            'available_tickets' => 100,
            'status' => 'active',
            'sort_order' => fake()->randomDigit(),
            'is_public' => true,
        ];
    }
}
