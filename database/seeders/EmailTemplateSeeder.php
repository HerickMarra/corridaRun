<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'slug' => 'boas-vindas',
                'name' => 'Boas-vindas (Novo Usuário)',
                'subject' => 'Bem-vindo à Corrida Final, @{nome}!',
                'content' => "Olá @{nome},\n\nSeja muito bem-vindo à nossa plataforma! É um prazer ter você conosco.\n\nAproveite para conferir as próximas corridas em nosso calendário e prepare sua meta para este ano!\n\nAtenciosamente,\nEquipe Corrida Final",
                'description' => 'Enviado automaticamente quando um novo atleta se cadastra no sistema.',
                'is_active' => true,
            ],
            [
                'slug' => 'confirmacao-inscricao',
                'name' => 'Confirmação de Inscrição',
                'subject' => 'Inscrição Confirmada: @{prova} - Pedido #@{inscricao}',
                'content' => "Olá @{nome},\n\nSua inscrição para a prova **@{prova}** foi confirmada com sucesso!\n\n**Detalhes da Prova:**\nData: @{data}\nPedido: #@{inscricao}\n\nVocê pode acessar todos os detalhes da sua inscrição no link abaixo:\n@{link_evento}\n\nPrepare-se bem e nos vemos na largada!\n\nAtenciosamente,\nEquipe Corrida Final",
                'description' => 'Enviado após a confirmação do pagamento de uma inscrição.',
                'is_active' => true,
            ],
            [
                'slug' => 'aguardando-pagamento',
                'name' => 'Aguardando Pagamento',
                'subject' => 'Seu pedido para @{prova} está aguardando pagamento',
                'content' => "Olá @{nome},\n\nRecebemos seu pedido para a prova **@{prova}**. Estamos aguardando a confirmação do pagamento para validar sua participação.\n\nNúmero do Pedido: #@{inscricao}\n\nCaso o pagamento já tenha sido realizado, por favor aguarde o processamento que ocorre em até 24h.\n\nAtenciosamente,\nEquipe Corrida Final",
                'description' => 'Enviado quando o pedido é gerado mas o pagamento ainda não foi confirmado.',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            \App\Models\EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
