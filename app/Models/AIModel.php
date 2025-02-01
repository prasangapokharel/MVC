<?php
namespace App\Models;

use GuzzleHttp\Client;

class AIModel
{
    private $openRouterApiKey;
    private $googleApiKey;
    private $client;

    public function __construct()
    {
        $config = require __DIR__ . '/../config.php';
        $this->openRouterApiKey = $config['openRouterApiKey'];
        $this->googleApiKey = $config['googleApiKey'];
        $this->client = new Client();
    }

    public function getChatResponse($message)
    {
        $response = $this->callOpenRouterAPI($message);
        if (!$response) {
            $response = $this->callGoogleGeminiAPI($message);
        }
        return $response ?? 'Sorry, I couldn\'t process your request.';
    }

    private function callOpenRouterAPI($message)
    {
        try {
            $response = $this->client->post('https://openrouter.ai/api/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->openRouterApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'openai/gpt-4o-2024-11-20',
                    'messages' => [['role' => 'user', 'content' => $message]]
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'] ?? null;
        } catch (\Exception $e) {
            error_log("OpenRouter API Error: " . $e->getMessage());
            return null;
        }
    }

    private function callGoogleGeminiAPI($message)
    {
        try {
            $response = $this->client->post("https://generativelanguage.googleapis.com/v1beta2/models/gemini-1.5-flash:generateText?key=" . $this->googleApiKey, [
                'json' => ['prompt' => ['text' => $message]]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['candidates'][0]['output'] ?? null;
        } catch (\Exception $e) {
            error_log("Google Gemini API Error: " . $e->getMessage());
            return null;
        }
    }
}

