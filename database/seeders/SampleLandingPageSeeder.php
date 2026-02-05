<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleLandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $template = \App\Models\LandingPageTemplate::where('identifier', 'tema-inicial')->first();

        \App\Models\LandingPage::updateOrCreate(
            ['slug' => 'run-the-city-2024'],
            [
                'title' => 'Run the City 2024',
                'landing_page_template_id' => $template->id,
                'is_active' => true,
                'content' => [
                    'hero' => [
                        'title' => 'Run the City 2024',
                        'subtitle' => 'Sinta a pulsação do asfalto. Junte-se a mais de 15.000 corredores no coração da cidade para uma experiência urbana de maratona inesquecível.',
                        'date_text' => '24 de Outubro, 2024',
                        'bg_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCSYPi156s6Om1es0kAjT3UWLXXVlfWUSUBDxg7bVuLneMINfHvHgaILtFP6enVa9m17hgX_4kMjICqHGfQGzjqhHmw3BrQfjDRkomM1E9XTmxfaUU8rKNP0Lhi6fLa-BZlwWUZn498lzSNCLQ1zLL3NFz6tp20-z81ojCkY042OI_oInhsNaCtvEG__s_9_JbTaPWWw6vYiQaDpUYnW5TlvvWt-jjFdWgagV0pvSX3D7y8Yn_TThsm7UQq-mE4U1OT0RvFKZ3JZ8I',
                        'cta_text' => 'Inscreva-se Agora',
                    ],
                    'about' => [
                        'title' => 'Sobre o Evento',
                        'description' => "Fundada em 2010 com o objetivo de conectar a comunidade através do esporte, a \"Run the City\" transformou-se de uma corrida local em um dos maiores eventos esportivos urbanos do país.\n\nA cada edição, buscamos rotas que contem uma história, passando por pontos turísticos icônicos e bairros repletos de cultura.",
                        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCSYPi156s6Om1es0kAjT3UWLXXVlfWUSUBDxg7bVuLneMINfHvHgaILtFP6enVa9m17hgX_4kMjICqHGfQGzjqhHmw3BrQfjDRkomM1E9XTmxfaUU8rKNP0Lhi6fLa-BZlwWUZn498lzSNCLQ1zLL3NFz6tp20-z81ojCkY042OI_oInhsNaCtvEG__s_9_JbTaPWWw6vYiQaDpUYnW5TlvvWt-jjFdWgagV0pvSX3D7y8Yn_TThsm7UQq-mE4U1OT0RvFKZ3JZ8I',
                        'stats' => [
                            ['label' => 'Anos de Tradição', 'value' => '14'],
                            ['label' => 'Kms Percorridos', 'value' => '150k+'],
                        ],
                    ],
                    'prices' => [
                        'items' => [
                            ['distance' => '5K', 'title' => 'Fun Run', 'description' => 'Perfeito para iniciantes e famílias.', 'price' => 'R$ 25', 'is_popular' => false],
                            ['distance' => '10K', 'title' => 'Challenge', 'description' => 'Para quem quer superar limites.', 'price' => 'R$ 40', 'is_popular' => false],
                            ['distance' => '21K', 'title' => 'Half Marathon', 'description' => 'O teste definitivo de resistência urbana.', 'price' => 'R$ 65', 'is_popular' => true],
                            ['distance' => '42K', 'title' => 'Full Marathon', 'description' => 'O ápice da conquista. 42km de puro orgulho.', 'price' => 'R$ 85', 'is_popular' => false],
                        ]
                    ]
                ]
            ]
        );
    }
}
