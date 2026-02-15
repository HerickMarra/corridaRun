<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    protected $apiKey;
    protected $model;
    protected $baseUrl = 'https://openrouter.ai/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.key');
        $this->model = config('services.openrouter.model');
    }

    /**
     * Generate response from OpenRouter
     */
    public function generateResponse(array $messages)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => config('app.url'), // Optional, for OpenRouter rankings
                'X-Title' => config('app.name'), // Optional, for OpenRouter rankings
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                        'model' => $this->model,
                        'messages' => $messages,
                    ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::error('OpenRouter API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OpenRouter Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
