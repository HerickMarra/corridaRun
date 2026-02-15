<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\OpenRouterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiSupportController extends Controller
{
    protected $aiService;
    protected $promptGenerator;

    public function __construct(OpenRouterService $aiService, \App\Services\AiPromptGenerator $promptGenerator)
    {
        $this->aiService = $aiService;
        $this->promptGenerator = $promptGenerator;
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
        // 1. Get Base Prompt from Visual Builder
        $basePrompt = $this->promptGenerator->generate();

        // 2. Dynamic User Context
        $user = auth()->user();
        $userContext = "DADOS DO USUÁRIO LOGADO:\n";
        if ($user) {
            $userContext .= "- Nome: {$user->name}\n";
            $userContext .= "- Email: {$user->email}\n";
            $userContext .= "- CPF: {$user->cpf}\n";
            $userContext .= "- Telefone: {$user->phone}\n";
            $userContext .= "- Localização: {$user->city}-{$user->state}\n";
        } else {
            $userContext .= "- Usuário não está logado.\n";
        }

        // 3. Dynamic Events Context
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

        // 4. Refund Policy (Hardcoded in prompt rules if needed, but keeping dynamic context separate)

        return $basePrompt . "\n\n" .
            "DADOS DINÂMICOS DO SISTEMA:\n" .
            $userContext . "\n" .
            $eventsContext;
    }
}
