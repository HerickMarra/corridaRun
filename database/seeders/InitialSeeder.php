<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuário Admin
        \App\Models\User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@teste.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => \App\Enums\UserRole::Admin,
            'cpf' => '000.000.000-00',
        ]);

        // Evento 1: Corrida das Cores 2026
        $event1 = \App\Models\Event::create([
            'name' => 'Corrida das Cores 2026',
            'slug' => 'corrida-das-cores-2026',
            'description' => 'Uma corrida vibrante e cheia de alegria pelas ruas da cidade.',
            'event_date' => now()->addMonths(3),
            'registration_start' => now(),
            'registration_end' => now()->addMonths(2),
            'location' => 'Parque da Cidade',
            'city' => 'São Paulo',
            'state' => 'SP',
            'max_participants' => 1000,
            'status' => \App\Enums\EventStatus::Published,
            'banner_image' => 'https://images.unsplash.com/photo-1541252260730-0442e3e7b003?q=80&w=1470&auto=format&fit=crop',
            'terms_and_conditions' => 'Termos de uso da corrida...',
        ]);

        $event1->categories()->createMany([
            ['name' => '5KM Individual', 'distance' => '5km', 'price' => 89.90, 'max_participants' => 400, 'available_tickets' => 400, 'status' => 'active'],
            ['name' => '10KM Individual', 'distance' => '10km', 'price' => 109.90, 'max_participants' => 400, 'available_tickets' => 400, 'status' => 'active'],
        ]);

        // Evento 2: Rio Trail 21k
        $event2 = \App\Models\Event::create([
            'name' => 'Rio Trail 21k',
            'slug' => 'rio-trail-21k',
            'description' => 'Desafie-se nas montanhas do Rio de Janeiro com vistas deslumbrantes.',
            'event_date' => now()->addMonths(2),
            'registration_start' => now(),
            'registration_end' => now()->addMonths(1),
            'location' => 'Parque Nacional da Tijuca',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'max_participants' => 500,
            'status' => \App\Enums\EventStatus::Published,
            'banner_image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=1470&auto=format&fit=crop',
            'terms_and_conditions' => 'Termos de uso trail run...',
        ]);

        $event2->categories()->createMany([
            ['name' => '12KM Trail', 'distance' => '12km', 'price' => 115.00, 'max_participants' => 250, 'available_tickets' => 250, 'status' => 'active'],
            ['name' => '21KM Trail', 'distance' => '21km', 'price' => 145.00, 'max_participants' => 250, 'available_tickets' => 250, 'status' => 'active'],
        ]);

        // Evento 3: Curitiba Marathon
        $event3 = \App\Models\Event::create([
            'name' => 'Curitiba Marathon 2026',
            'slug' => 'curitiba-marathon-2026',
            'description' => 'A maratona mais gelada e charmosa do Brasil.',
            'event_date' => now()->addMonths(5),
            'registration_start' => now()->addMonths(1),
            'registration_end' => now()->addMonths(4),
            'location' => 'Centro Cívico',
            'city' => 'Curitiba',
            'state' => 'PR',
            'max_participants' => 2000,
            'status' => \App\Enums\EventStatus::Published,
            'banner_image' => 'https://images.unsplash.com/photo-1532444458054-01a7dd3e9fca?q=80&w=1470&auto=format&fit=crop',
            'terms_and_conditions' => 'Termos de uso maratona...',
        ]);

        $event3->categories()->createMany([
            ['name' => '42KM Marathon', 'distance' => '42km', 'price' => 189.90, 'max_participants' => 1000, 'available_tickets' => 1000, 'status' => 'active'],
            ['name' => '21KM Half Marathon', 'distance' => '21km', 'price' => 129.90, 'max_participants' => 1000, 'available_tickets' => 1000, 'status' => 'active'],
        ]);

        // Evento 4: SP Night Run
        $event4 = \App\Models\Event::create([
            'name' => 'SP Night Run',
            'slug' => 'sp-night-run',
            'description' => 'Sinta a energia da noite paulistana correndo em alta velocidade.',
            'event_date' => now()->addWeeks(6),
            'registration_start' => now(),
            'registration_end' => now()->addWeeks(5),
            'location' => 'Marginal Pinheiros',
            'city' => 'São Paulo',
            'state' => 'SP',
            'max_participants' => 3000,
            'status' => \App\Enums\EventStatus::Published,
            'banner_image' => 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?q=80&w=1470&auto=format&fit=crop',
            'terms_and_conditions' => 'Termos de uso night run...',
        ]);

        $event4->categories()->createMany([
            ['name' => '5KM Night', 'distance' => '5km', 'price' => 95.00, 'max_participants' => 1500, 'available_tickets' => 1500, 'status' => 'active'],
            ['name' => '10KM Night', 'distance' => '10km', 'price' => 110.00, 'max_participants' => 1500, 'available_tickets' => 1500, 'status' => 'active'],
        ]);
    }
}
