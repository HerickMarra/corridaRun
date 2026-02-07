<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        \App\Models\EmailTemplate::create([
            'slug' => 'welcome',
            'name' => 'Boas-vindas (Novo Registro)',
            'subject' => 'Bem-vindo(a) à Sisters Esportes, @{nome}!',
            'content' => "## Olá @{nome}, seja muito bem-vindo(a)!\n\nEstamos muito felizes em ter você conosco na plataforma Sisters Esportes. Aqui você encontrará os melhores desafios e eventos de corrida da região.\n\nSua conta foi criada com sucesso e você já pode explorar nosso calendário de eventos e realizar suas inscrições.\n\n### O que fazer agora?\n1. Complete seu perfil para facilitar suas inscrições.\n2. Explore o [Calendário de Eventos](" . config('app.url') . "/calendar).\n3. Prepare seus tênis!\n\nSe precisar de ajuda, basta responder a este e-mail.\n\nUm grande abraço,\n**Equipe Sisters Esportes**",
            'description' => 'E-mail enviado automaticamente após o usuário criar uma conta na plataforma.',
            'is_active' => true,
        ]);

        \App\Models\EmailTemplate::create([
            'slug' => 'order_confirmation',
            'name' => 'Confirmação de Inscrição',
            'subject' => 'Inscrição Confirmada: @{prova}',
            'content' => "## Olá @{nome}, sua inscrição está confirmada!\n\nÉ com prazer que informamos que sua inscrição para a prova **@{prova}** foi processada com sucesso.\n\n**Detalhes da Inscrição:**\n- **Inscrição:** @{inscricao}\n- **Data do Evento:** @{data}\n- **Evento:** [Acessar página da prova](@{link_evento})\n\nVocê já pode conferir todos os detalhes e o comprovante na sua área do corredor.\n\nPrepare-se bem e nos vemos na linha de largada!\n\nAtenciosamente,\n**Equipe Sisters Esportes**",
            'description' => 'E-mail enviado após a confirmação do pagamento ou conclusão de inscrição em evento gratuito.',
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        \App\Models\EmailTemplate::whereIn('slug', ['welcome', 'order_confirmation'])->delete();
    }
};
