<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Enums\EventStatus;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create athletes
        $users = User::factory(100)->create();

        // 2. Create Future Events (Active)
        Event::factory(5)->create()->each(function ($event) use ($users) {
            $categories = Category::factory(3)->create(['event_id' => $event->id]);

            foreach ($categories as $category) {
                $orderCount = rand(5, 20);
                for ($i = 0; $i < $orderCount; $i++) {
                    $user = $users->random();

                    $start = $event->registration_start < now() ? $event->registration_start : now()->subDays(30);
                    $createdAt = fake()->dateTimeBetween($start, 'now');

                    $order = Order::factory()->create([
                        'user_id' => $user->id,
                        'total_amount' => $category->price + ($category->price * 0.07),
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'category_id' => $category->id,
                        'participant_name' => $user->name,
                        'participant_email' => $user->email,
                        'price' => $category->price,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        });

        // 3. Create Finished Events (Closed)
        Event::factory(10)->state(['status' => EventStatus::Closed])->finished()->create()->each(function ($event) use ($users) {
            $categories = Category::factory(3)->create(['event_id' => $event->id]);

            foreach ($categories as $category) {
                $orderCount = rand(15, 40);
                for ($i = 0; $i < $orderCount; $i++) {
                    $user = $users->random();

                    $start = $event->registration_start < $event->registration_end ? $event->registration_start : (clone $event->registration_end)->modify('-30 days');
                    $createdAt = fake()->dateTimeBetween($start, $event->registration_end);

                    $order = Order::factory()->create([
                        'user_id' => $user->id,
                        'status' => OrderStatus::Paid,
                        'total_amount' => $category->price + ($category->price * 0.07),
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'category_id' => $category->id,
                        'participant_name' => $user->name,
                        'participant_email' => $user->email,
                        'price' => $category->price,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        });

        // 4. Create Sold Out Event
        $soldOutEvent = Event::factory()->create([
            'name' => 'Maratona Esgotada 2026',
            'slug' => 'maratona-esgotada-2026',
            'max_participants' => 30,
            'registration_start' => now()->subMonths(2),
            'registration_end' => now()->addMonths(1),
        ]);
        $category = Category::factory()->create([
            'event_id' => $soldOutEvent->id,
            'max_participants' => 30,
            'available_tickets' => 0,
        ]);

        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $createdAt = fake()->dateTimeBetween($soldOutEvent->registration_start, 'now');

            $order = Order::factory()->create([
                'user_id' => $user->id,
                'status' => OrderStatus::Paid,
                'total_amount' => $category->price + ($category->price * 0.07),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            OrderItem::factory()->create([
                'order_id' => $order->id,
                'category_id' => $category->id,
                'price' => $category->price,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
