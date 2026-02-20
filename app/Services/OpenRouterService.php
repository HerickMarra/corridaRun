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
        $maxRetries = 3;
        $attempt = 0;
        $delayBase = 1; // Base delay in seconds for exponential backoff

        while ($attempt < $maxRetries) {
            $attempt++;
            try {
                $response = Http::timeout(10)->withHeaders([
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

                Log::warning("OpenRouter API Error Attempt {$attempt}/{$maxRetries}", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::warning("OpenRouter Timeout/Connection Error Attempt {$attempt}/{$maxRetries}", ['error' => $e->getMessage()]);
            } catch (\Exception $e) {
                Log::error("OpenRouter Exception Attempt {$attempt}/{$maxRetries}", ['error' => $e->getMessage()]);
            }

            // If it's not the last attempt, wait before trying again
            if ($attempt < $maxRetries) {
                // Exponential backoff: 1s, 2s
                sleep($delayBase * $attempt);
            }
        }

        Log::error('OpenRouter Maximum Retries Reached. Returning null.');
        return null;
    }
}
