<?php

namespace Godsu\Mvc\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Exception;
use Psr\SimpleCache\CacheInterface;

class ChatService
{
    private $client;
    private $cache;
    
    // OpenRouter API Keys
    private $openRouterKeys = [
        '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',
        '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',
        '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'
    ];
    
    private $currentKeyIndex = 0;
    private $retryAttempts = 3;
    private $cacheExpiration = 3600; // 1 hour

    // Paths for the trained module
    private $trainedModulePath = __DIR__ . '/trained-models/module.json';

    public function __construct(CacheInterface $cache = null)
    {
        $this->client = new Client([
            'timeout' => 60,
            'verify' => false
        ]);
        $this->cache = $cache;
    }

    public function streamResponse($message)
    {
        // Check cache first
        if ($this->cache) {
            $cacheKey = md5($message);
            $cachedResponse = $this->cache->get($cacheKey);
            if ($cachedResponse) {
                return $this->createStreamFromString($cachedResponse);
            }
        }

        // Check trained module
        $trainedResponse = $this->getTrainedResponse($message);
        if ($trainedResponse) {
            return $this->createStreamFromString($trainedResponse);
        }

        // Fallback to API response
        return $this->streamOpenRouterResponse($message);
    }

    private function getTrainedResponse($message)
    {
        if (file_exists($this->trainedModulePath)) {
            $trainedData = json_decode(file_get_contents($this->trainedModulePath), true);
            if (isset($trainedData['responses'][$message])) {
                return $trainedData['responses'][$message];
            }
        }
        return null;
    }

    private function streamOpenRouterResponse($message, $attempt = 0)
    {
        try {
            $currentKey = $this->openRouterKeys[$this->currentKeyIndex];
            
            $response = $this->client->post('https://openrouter.ai/api/v1/chat/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $currentKey,
                    'HTTP-Referer' => 'http://localhost:8000',
                    'X-Title' => 'Nepal AI'
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an AI assistant specialized in providing clear, concise, and accurate information. Use markdown formatting when appropriate.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $message
                        ]
                    ],
                    'stream' => true
                ],
                'stream' => true
            ]);

            return $this->processStream($response->getBody(), $message);

        } catch (GuzzleException $e) {
            // Rotate API key and retry if possible
            $this->rotateApiKey();
            
            if ($attempt < $this->retryAttempts) {
                return $this->streamOpenRouterResponse($message, $attempt + 1);
            }

            return $this->createErrorStream('An error occurred. Please try again later.');
        }
    }

    private function rotateApiKey()
    {
        $this->currentKeyIndex = ($this->currentKeyIndex + 1) % count($this->openRouterKeys);
    }

    private function processStream($stream, $originalMessage)
    {
        return new class($stream, $originalMessage, $this->cache) {
            private $stream;
            private $buffer = '';
            private $completeResponse = '';
            private $done = false;
            private $originalMessage;
            private $cache;

            public function __construct($stream, $originalMessage, $cache) {
                $this->stream = $stream;
                $this->originalMessage = $originalMessage;
                $this->cache = $cache;
            }

            public function read($length)
            {
                if ($this->done) {
                    return '';
                }

                while (!$this->stream->eof()) {
                    $chunk = $this->stream->read($length);
                    $this->buffer .= $chunk;

                    $lines = explode("\n", $this->buffer);
                    $this->buffer = array_pop($lines);

                    foreach ($lines as $line) {
                        if (strpos($line, 'data: ') === 0) {
                            $data = substr($line, 6);
                            if ($data === '[DONE]') {
                                $this->done = true;
                                
                                // Cache the complete response
                                if ($this->cache) {
                                    $this->cache->set(
                                        md5($this->originalMessage),
                                        $this->completeResponse,
                                        3600
                                    );
                                }
                                
                                return $this->sendCompleteResponse();
                            }
                            
                            $decoded = json_decode($data, true);
                            if (isset($decoded['choices'][0]['delta']['content'])) {
                                $this->completeResponse .= $decoded['choices'][0]['delta']['content'];
                            }
                        }
                    }
                }

                if (!empty($this->buffer)) {
                    $temp = $this->buffer;
                    $this->buffer = '';
                    return $temp;
                }

                $this->done = true;
                return $this->sendCompleteResponse();
            }

            private function sendCompleteResponse()
            {
                $response = "data: " . json_encode([
                    'choices' => [
                        ['delta' => ['content' => $this->completeResponse]]
                    ]
                ]) . "\n\n";
                $response .= "data: [DONE]\n\n";
                return $response;
            }

            public function eof()
            {
                return $this->done && empty($this->buffer);
            }
        };
    }

    private function createStreamFromString($content)
    {
        return new class($content) {
            private $content;
            private $sent = false;

            public function __construct($content) {
                $this->content = $content;
            }

            public function read($length)
            {
                if ($this->sent) {
                    return "data: [DONE]\n\n";
                }
                $this->sent = true;
                return "data: " . json_encode([
                    'choices' => [
                        ['delta' => ['content' => $this->content]]
                    ]
                ]) . "\n\ndata: [DONE]\n\n";
            }

            public function eof()
            {
                return $this->sent;
            }
        };
    }

    private function createErrorStream($message)
    {
        return $this->createStreamFromString($message);
    }
}