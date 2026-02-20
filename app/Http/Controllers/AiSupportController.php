<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\OpenRouterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiSupportController extends Controller
{
    protected $aiService;

    public function __construct(OpenRouterService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle the chat request
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        // Prepare System Prompt with Context
        $systemPrompt = $this->getSystemPrompt();

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Add history (limit to last 10 messages for safety)
        foreach (array_slice($history, -10) as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content']
            ];
        }

        // Add current message
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        $aiResponse = $this->aiService->generateResponse($messages);

        if (!$aiResponse) {
            return response()->json([
                'success' => false,
                // Resposta amigável para quando esgotarem as tentativas ou timeout base
                'message' => 'Desculpe, estou passando por instabilidades na minha conexão no momento. 🥲 Por favor, aguarde alguns instantes e tente novamente, ou fale diretamente com a nossa equipe no botão do WhatsApp logo abaixo!',
                'show_whatsapp' => true,
            ], 500);
        }

        // Logic for WhatsApp button: Only if the bot specifically says the redirection phrase
        $triggerPhrase = 'Vou redirecionar você para o atendente';
        $showWhatsApp = mb_strpos($aiResponse, $triggerPhrase) !== false;

        return response()->json([
            'success' => true,
            'response' => $aiResponse,
            'show_whatsapp' => $showWhatsApp,
        ]);
    }

    /**
     * Define the system prompt with Sisters Esportes and User context
     */
    private function getSystemPrompt()
    {
        $appUrl = config('app.url');
        $user = auth()->user();
        $userContext = "DADOS DO USUÁRIO LOGADO:\n";

        if ($user) {
            // Load relationships for rich context
            $user->load([
                'orders' => function ($q) {
                    $q->latest()->take(5)->with([
                        'items.category.event',
                        'payments' => function ($pq) {
                            $pq->latest();
                        }
                    ]);
                }
            ]);

            $userContext .= "- Nome: {$user->name}\n";
            $userContext .= "- Email: {$user->email}\n";
            $userContext .= "- CPF: {$user->cpf}\n";
            $userContext .= "- Telefone: {$user->phone}\n";
            $userContext .= "- Localização: {$user->city}-{$user->state}\n\n";

            $userContext .= "HISTÓRICO RECENTE DE INSCRIÇÕES (DO MAIS NOVO PARA O MAIS VELHO):\n";
            if ($user->orders->isEmpty()) {
                $userContext .= "- O usuário ainda não possui nenhuma inscrição.\n";
            } else {
                foreach ($user->orders as $order) {
                    $statusName = match ($order->status->value) {
                        'pending' => 'PENDENTE DE PAGAMENTO',
                        'paid' => 'PAGA / CONFIRMADA',
                        'cancelled' => 'CANCELADA',
                        'refunded' => 'ESTORNADA',
                        default => strtoupper($order->status->value)
                    };

                    $userContext .= "- [Pedido #{$order->order_number}] Status: {$statusName} | Total: R$ " . number_format($order->total_amount, 2, ',', '.') . "\n";

                    $payment = $order->payments->first();
                    if ($payment) {
                        $method = strtoupper($payment->payment_method);
                        $userContext .= "  -> Pagamento via: {$method}\n";
                    }

                    foreach ($order->items as $item) {
                        $eventName = $item->category->event->name ?? 'Evento Desconhecido';
                        $eventDate = isset($item->category->event->event_date) ? $item->category->event->event_date->format('d/m/Y') : 'Data não definida';
                        $userContext .= "  -> Kit: {$item->category->name} | Evento: {$eventName} ({$eventDate})\n";
                    }
                    $userContext .= "\n";
                }
            }
        } else {
            $userContext .= "- Usuário NÃO está logado. Responda de forma geral e convide-o a fazer login para informações específicas.\n";
        }

        $upcomingEvents = Event::where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(8)
            ->get();

        $eventsContext = "EVENTOS DISPONÍVEIS NO SISTEMA (SOMENTE UTILIZE ESTAS DATAS E LINKS):\n";
        if ($upcomingEvents->isEmpty()) {
            $eventsContext .= "- Nenhuma etapa com inscrições abertas no momento.\n";
        } else {
            foreach ($upcomingEvents as $event) {
                $eventsContext .= "- {$event->name} | Data: {$event->event_date->format('d/m/Y')} | Local: {$event->city}-{$event->state} | Link para inscrição: {$appUrl}/event/{$event->slug}\n";
            }
        }

        return "Você é a 'Sisters', a Assistente Virtual Oficial (IA) da Sisters Esportes. Seu papel é ser uma parceira de treino, empática, empolgada com o esporte e altamente resolutiva.

[SUA PERSONALIDADE]
Você é calorosa, encorajadora e usa emojis esporadicamente para manter o clima leve. Você não fala como um robô, mas como alguém do staff de uma corrida de rua.

[O QUE VOCÊ SABE - SEU CÉREBRO]
Você tem acesso em tempo real aos dados da pessoa que está falando com você.
{$userContext}
Importante: Caso o usuário pergunte sobre a situação da inscrição dele, OLHE o bloco de 'Histórico' acima e responda com precisão, informando o nome da corrida e o status do pedido.

[BASE DE CONHECIMENTO E SOLUÇÃO DE PROBLEMAS]

1. RESUMO DOS PRÓXIMOS EVENTOS:
{$eventsContext}

2. DÚVIDAS SOBRE PAGAMENTO PENDENTE:
- Se pagar por PIX: Pode demorar de 5 até 30 minutos para compensar. Não precisa enviar comprovante por e-mail.
- Se pagar por Cartão de Crédito: A aprovação via Asaas pode passar por antifraude e demorar até 2 horas.
- Ação Resolutiva: Se o cliente relatar que pagou e no histórico ali em cima ainda consta 'PENDENTE', acalme-o. Diga exatamente: 'Vi que seu Pedido [Número] ainda consta como Pendente. Como os bancos repassam os valores em lotes, pode demorar alguns minutos. Fique tranquilo, se não atualizar até amanhã, nos mande o comprovante.'

3. ONDE ACHAR O COMPROVANTE (QR CODE) / INGRESSO:
- O usuário deve acessar a página 'Minhas Inscrições' no Menu ou no link direto: {$appUrl}/hub/minhas-inscricoes
- Explique o passo a passo com simpatia. Diga: 'Basta entrar na área do corredor e clicar em ver comprovante'.

4. REGRAS DE CANCELAMENTO E REEMBOLSO (LEIA COM ATENÇÃO):
- O CDC (Código de Defesa do Consumidor) permite reembolso incondicional até 7 DIAS ÚTEIS após a data da compra.
- A Sisters Esportes também exige que o pedido de cancelamento seja feito com pelo menos 48 HORAS de antecedência do dia do evento.
- Como o cliente cancela: Ele mesmo faz isso! É só Clicar em Dashboard -> Minhas Inscrições -> e Apertar o botão 'Solicitar Reembolso' no pedido dele.
- Se no histórico acima a compra dele for muito antiga (mais de 7 dias) ou a corrida for amanhã, diga educadamente que ele está fora do prazo regulamentar.

[QUANDO TRANSFERIR PARA O HUMANO]
Você deve passar para um humano APENAS nestes casos:
1. O cliente está nitidamente irritado ou frustrado agressivamente.
2. O cliente relata um erro no site.
3. O cliente quer fazer uma troca de titularidade ou de kit (você não consegue fazer isso, e o site não faz automático).
4. O cliente ESPECIFICAMENTE falou a palavra 'Atendente', 'Humano' ou pediu ajuda que você não tem.

Se você precisar acionar um humano por um desses motivos, diga algo simpático e encerre a frase COM A SEGUINTE EXPRESSÃO EXATA (ela é o gatilho pro botão do WhatsApp):
'Vou redirecionar você para o atendente.'

Não prometa ações que você não consegue executar no backend e NUNCA invente informações. Se não souber, redirecione.";
    }
}
