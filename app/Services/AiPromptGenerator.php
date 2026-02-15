<?php

namespace App\Services;

use App\Models\AiNode;
use App\Models\AiConnection;

class AiPromptGenerator
{
    /**
     * Generate the consolidated system prompt from the visual graph.
     */
    public function generate(): string
    {
        $nodes = AiNode::with('outgoingConnections')->get();

        if ($nodes->isEmpty()) {
            return $this->getDefaultPrompt();
        }

        $personality = [];
        $emotions = [];
        $instructions = [];
        $context = [];

        foreach ($nodes as $node) {
            switch ($node->type) {
                case 'personality':
                    $personality[] = $node->content;
                    break;
                case 'emotion':
                    $emotions[] = $node->content;
                    break;
                case 'instruction':
                    $instructions[] = $node->content;
                    break;
                case 'context':
                    $context[] = $node->content;
                    break;
            }
        }

        $prompt = "Você é o Assistente Virtual da Sisters Esportes.\n\n";

        if (!empty($personality)) {
            $prompt .= "SUA PERSONALIDADE:\n" . implode("\n", array_filter($personality)) . "\n\n";
        }

        if (!empty($emotions)) {
            $prompt .= "TOM DE VOZ E EMOÇÕES:\n" . implode("\n", array_filter($emotions)) . "\n\n";
        }

        if (!empty($context)) {
            $prompt .= "CONTEXTO ADICIONAL:\n" . implode("\n", array_filter($context)) . "\n\n";
        }

        if (!empty($instructions)) {
            $prompt .= "REGRAS E INSTRUÇÕES:\n" . implode("\n", array_filter($instructions)) . "\n\n";
        }

        // Add some hardcoded constraints that are essential
        $prompt .= "DIRETRIZES FINAIS:
- Responda sempre em Português do Brasil.
- Use formatação Markdown.
- Se não souber algo, admita e ofereça redirecionamento humanizado.";

        return $prompt;
    }

    /**
     * Fallback prompt if no nodes exist.
     */
    private function getDefaultPrompt(): string
    {
        return "Você é o Assistente Virtual da Sisters Esportes. Seu papel é buscar e fornecer INFORMAÇÕES precisas.
        Seja conciso, motivador e profissional.";
    }
}
