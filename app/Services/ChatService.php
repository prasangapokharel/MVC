<?php

namespace Godsu\Mvc\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ChatService
{
    private $client;
    private $openRouterKey = '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';

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 60,
            'verify' => false
        ]);
    }

    public function streamResponse($message)
    {
        try {
            $response = $this->client->post('https://openrouter.ai/api/v1/chat/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->openRouterKey,
                    'HTTP-Referer' => 'http://localhost:8000',
                    'X-Title' => 'Nepal AI'
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI assistant specialized in providing clear, concise, and accurate information. Use markdown formatting when appropriate.'],
                        ['role' => 'user', 'content' => $message]
                    ],
                    'stream' => true
                ],
                'stream' => true
            ]);

            return $this->processStream($response->getBody());
        } catch (GuzzleException $e) {
            return $this->createErrorStream('An error occurred. Please try again later.');
        }
    }

    private function processStream($stream)
    {
        return new class($stream) {
            private $stream;
            private $buffer = '';
            private $completeResponse = '';
            private $done = false;

            public function __construct($stream) {
                $this->stream = $stream;
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

    private function createErrorStream($message)
    {
        return new class($message) {
            private $message;
            private $sent = false;

            public function __construct($message) {
                $this->message = $message;
            }

            public function read($length) {
                if ($this->sent) {
                    return "data: [DONE]\n\n";
                }
                $this->sent = true;
                return "data: " . json_encode([
                    'choices' => [
                        ['delta' => ['content' => $this->message]]
                    ]
                ]) . "\n\ndata: [DONE]\n\n";
            }

            public function eof() {
                return $this->sent;
            }
        };
    }
}

