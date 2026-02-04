<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Geral
            [
                'key' => 'site_name',
                'value' => 'RunPace',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Nome do Site',
                'description' => 'O nome da plataforma exibido em todo o site.',
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'group' => 'general',
                'type' => 'file',
                'label' => 'Logo do Site',
                'description' => 'A logo principal da plataforma.',
            ],
            [
                'key' => 'contact_email',
                'value' => 'contato@runpace.com.br',
                'group' => 'general',
                'type' => 'text',
                'label' => 'E-mail de Contato',
                'description' => 'E-mail oficial para suporte aos atletas.',
            ],

            // Pagamentos
            [
                'key' => 'service_fee_percent',
                'value' => '7',
                'group' => 'payment',
                'type' => 'number',
                'label' => 'Taxa de Serviço (%)',
                'description' => 'Porcentagem cobrada sobre o valor da inscrição.',
            ],
            [
                'key' => 'mercadopago_public_key',
                'value' => 'APP_USR-xxxx-xxxx',
                'group' => 'payment',
                'type' => 'text',
                'label' => 'Mercado Pago Public Key',
                'description' => 'Chave pública para processamento de pagamentos.',
            ],
            [
                'key' => 'mercadopago_access_token',
                'value' => 'APP_USR-xxxx-xxxx',
                'group' => 'payment',
                'type' => 'password',
                'label' => 'Mercado Pago Access Token',
                'description' => 'Token de acesso privado do Mercado Pago.',
            ],

            // Redes Sociais
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/runpace',
                'group' => 'social',
                'type' => 'text',
                'label' => 'Instagram',
                'description' => 'Link do perfil oficial no Instagram.',
            ],
            [
                'key' => 'social_whatsapp',
                'value' => 'https://wa.me/5500000000000',
                'group' => 'social',
                'type' => 'text',
                'label' => 'WhatsApp',
                'description' => 'Link direto para suporte via WhatsApp.',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
