<?php

namespace Database\Factories;

use App\Models\Event;
use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $name = fake()->words(3, true) . ' ' . fake()->year();
        $eventDate = fake()->dateTimeBetween('-1 month', '+6 months');

        $images = [
            'https://images.unsplash.com/photo-1541252260730-0442e3e7b003?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1532444458054-01a7dd3e9fca?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1452626038306-9aae0e073b4f?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1502904550040-7534597429ae?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1530541930197-ff16ac917b0e?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1444491741275-3747c33cc99b?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1534067783941-51c9c23ecefd?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1530143311094-34d807799e8f?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1483728642387-6c3bdd6c93e5?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1452626038306-9aae0e073b4f?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1562183241-b937e95585b6?q=80&w=1470&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1486326233492-b2f703e7e23a?q=80&w=1470&auto=format&fit=crop',
        ];

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraphs(3, true),
            'event_date' => $eventDate,
            'registration_start' => (clone $eventDate)->modify('-4 months'),
            'registration_end' => (clone $eventDate)->modify('-1 week'),
            'location' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'max_participants' => fake()->randomElement([500, 1000, 2000, 5000]),
            'status' => EventStatus::Published,
            'banner_image' => fake()->randomElement($images),
            'terms_and_conditions' => fake()->text(),
            'regulation' => fake()->realText(1000),
        ];
    }

    public function finished()
    {
        return $this->state(function (array $attributes) {
            $eventDate = fake()->dateTimeBetween('-1 year', '-2 months');
            return [
                'event_date' => $eventDate,
                'registration_start' => (clone $eventDate)->modify('-5 months'),
                'registration_end' => (clone $eventDate)->modify('-1 week'),
            ];
        });
    }
}
