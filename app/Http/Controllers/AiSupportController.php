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
                'message' => 'Desculpe, estou com dificuldades técnicas no momento. Por favor, tente novamente ou fale com nosso suporte via WhatsApp.',
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
        $user = auth()->user();
        $userContext = "DADOS DO USUÁRIO LOGADO:\n";
        if ($user) {
            $userContext .= "- Nome: {$user->name}\n";
            $userContext .= "- Email: {$user->email}\n";
            $userContext .= "- CPF: {$user->cpf}\n";
            $userContext .= "- Telefone: {$user->phone}\n";
            $userContext .= "- Localização: {$user->city}-{$user->state}\n";
            // More data can be added here if needed
        } else {
            $userContext .= "- Usuário não está logado.\n";
        }

        $upcomingEvents = Event::where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(8)
            ->get();

        $eventsContext = "Eventos Futuros Confirmados:\n";
        if ($upcomingEvents->isEmpty()) {
            $eventsContext .= "- Nenhuma etapa com inscrições abertas no momento.\n";
        } else {
            foreach ($upcomingEvents as $event) {
                $eventsContext .= "- {$event->name}: {$event->event_date->format('d/m/Y')} em {$event->city}-{$event->state}. [Link: sistersesportes.com.br/eventos/{$event->slug}]\n";
            }
        }

        return "Você é o Assistente Virtual da Sisters Esportes. Seu papel é buscar e fornecer INFORMAÇÕES precisas.

        CONDIÇÃO CRÍTICA DE OPERAÇÃO:
        - Você NÃO PODE realizar ações (cancelar pedidos, alterar dados, processar reembolsos, etc.).
        - Você fornece apenas informações. Se o usuário pedir algo que exija ação, você deve encaminhá-lo para um atendente humano.

        POLÍTICA DE REEMBOLSO (REGRAS):
        1. O reembolso pode ser solicitado em até **7 dias após a data da compra**.
        2. A solicitação deve ocorrer, no mínimo, **48 horas antes do evento** acontecer.
        3. O usuário deve solicitar o reembolso diretamente na **tela de inscrição da corrida** no seu painel.
        4. Caso o usuário encontre dificuldades, ele deve solicitar ajuda a um **atendente humano**.

        CONTEXTO DO USUÁRIO ATUAL:
        {$userContext}

        INSTRUÇÕES ESPECÍFICAS:
        - **COMO PEGAR O COMPROVANTE**: Para acessar o comprovante da corrida, o usuário deve ir à [Área do Corredor](/hub/dashboard), acessar a seção 'Minhas Inscrições' e clicar no botão 'Comprovante'.
        - **CALENDÁRIO DE CORRIDAS**: O calendário completo de eventos está disponível em [/calendario](/calendario).
        - **PAGAMENTO NÃO CONFIRMADO**: Se o usuário relatar que pagou e não confirmou, explique que o **Pix pode levar até 30 minutos** e **Cartão de Crédito até 2 horas** para compensar. Se já passou desse prazo, peça para ele reunir os comprovantes/prints e solicitar falar com um atendente.

        CONTEXTO DOS EVENTOS:
        {$eventsContext}

        REGRAS DE OURO:
        - Seja conciso e motivador. Use Negrito para destacar pontos importantes.
        - **IMPORTANTE SOBRE REDIRECIONAMENTO**: Você só deve escrever a frase EXATA: 'Vou redirecionar você para o atendente.' se o usuário pedir **explicitamente** para falar com um atendente, humano ou suporte.
        - **OBSERVAÇÃO SOBRE FALTA DE DADOS**: Se você não tiver a informação solicitada (ex: detalhes de um evento não listado), diga: \"Não tenho essa informação no momento.\" e pergunte se ele deseja falar com um atendente. SE ele confirmar que quer, aí sim use a frase de redirecionamento.
        - Se o usuário pedir algo que você não pode fazer (como uma ação), explique que você é um assistente virtual e que para isso ele deve procurar um atendente, mas NÃO use a frase de redirecionamento a menos que ele confirme que deseja falar com um.
        - Responda em Português do Brasil.";
    }
}
