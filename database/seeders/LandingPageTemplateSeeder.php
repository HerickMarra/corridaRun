<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\LandingPageTemplate::updateOrCreate(
            ['identifier' => 'tema-inicial'],
            [
                'name' => 'Tema Inicial (Maratona)',
                'config_schema' => [
                    'hero' => [
                        ['key' => 'title', 'label' => 'Título Principal', 'type' => 'text'],
                        ['key' => 'subtitle', 'label' => 'Subtítulo', 'type' => 'textarea'],
                        ['key' => 'date_text', 'label' => 'Texto da Data', 'type' => 'text'],
                        ['key' => 'bg_image', 'label' => 'Imagem de Fundo', 'type' => 'image'],
                        ['key' => 'cta_text', 'label' => 'Texto do Botão', 'type' => 'text'],
                    ],
                    'about' => [
                        ['key' => 'title', 'label' => 'Título Sobre', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Descrição', 'type' => 'textarea'],
                        ['key' => 'image', 'label' => 'Imagem Lateral', 'type' => 'image'],
                        [
                            'key' => 'stats',
                            'label' => 'Estatísticas',
                            'type' => 'array',
                            'item_schema' => [
                                ['key' => 'label', 'label' => 'Etiqueta', 'type' => 'text'],
                                ['key' => 'value', 'label' => 'Valor', 'type' => 'text'],
                            ]
                        ],
                    ],
                    'prices' => [
                        [
                            'key' => 'items',
                            'label' => 'Cards de Preço/Distância',
                            'type' => 'array',
                            'item_schema' => [
                                ['key' => 'distance', 'label' => 'Distância (ex: 42K)', 'type' => 'text'],
                                ['key' => 'title', 'label' => 'Título (ex: Maratona)', 'type' => 'text'],
                                ['key' => 'description', 'label' => 'Descrição Curta', 'type' => 'text'],
                                ['key' => 'price', 'label' => 'Preço', 'type' => 'text'],
                                ['key' => 'is_popular', 'label' => 'Destaque?', 'type' => 'boolean'],
                            ]
                        ],
                    ],
                ]
            ]
        );
    }
}
