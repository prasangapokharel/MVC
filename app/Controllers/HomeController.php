<?php

namespace Godsu\Mvc\Controllers;

use Godsu\Mvc\Services\ChatService;
use Exception;

class HomeController
{
    private $chatService;
    private $sessionKey = 'nepal_ai_chat_history';
    private $maxHistoryMessages = 50;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $this->chatService = new ChatService();
        } catch (Exception $e) {
            error_log("ChatService Error: " . $e->getMessage());
            $this->chatService = null;
        }

        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [];
        }
    }

    public function index()
    {
        if ($this->isAjaxRequest()) {
            return $this->handleChatRequest();
        }

        $chatHistory = $_SESSION[$this->sessionKey] ?? [];
        require __DIR__ . '/../views/home.php';
    }

    private function handleChatRequest()
    {
        if (!$this->chatService) {
            $this->sendErrorResponse($this->formatErrorResponse('Service temporarily unavailable'), 503);
            return;
        }

        $message = $this->getValidatedMessage();
        if (empty($message)) {
            $this->sendErrorResponse($this->formatErrorResponse('Message is required'), 400);
            return;
        }

        try {
            $this->setupStreamHeaders();
            $this->processStreamResponse($message);
        } catch (Exception $e) {
            error_log("Stream Error: " . $e->getMessage());
            $this->sendErrorResponse($this->formatErrorResponse('Error processing request'), 500);
        }
    }

    private function processStreamResponse($message)
    {
        if ($this->isDuplicateQuestion($message)) {
            $this->sendDuplicateResponse();
            exit;
        }

        $this->addToHistory([
            'role' => 'user',
            'content' => $this->formatUserMessage($message),
            'timestamp' => time()
        ]);

        $stream = $this->chatService->streamResponse($message);
        $aiResponse = '';

        while (!$stream->eof()) {
            $chunk = $stream->read(1024);
            echo $chunk;
            ob_flush();
            flush();

            if (strpos($chunk, '[DONE]') !== false) {
                break;
            }
        }

        exit;
    }

    private function formatUserMessage($message)
    {
        return <<<EOT
**User Query**: 
{$message}
EOT;
    }

    private function addToHistory($message)
    {
        $_SESSION[$this->sessionKey][] = $message;

        if (count($_SESSION[$this->sessionKey]) > $this->maxHistoryMessages) {
            array_shift($_SESSION[$this->sessionKey]);
        }
    }

    private function setupStreamHeaders()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
    }

    private function isAjaxRequest()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST' && 
               isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function getValidatedMessage()
    {
        $message = trim($_POST['message'] ?? '');
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        
        if (strlen($message) > 2000) {
            $message = substr($message, 0, 2000);
        }

        return $message;
    }

    private function sendErrorResponse($data, $code = 400)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function isDuplicateQuestion($message)
    {
        $history = $_SESSION[$this->sessionKey] ?? [];
        $formattedMessage = $this->formatUserMessage($message);
        
        foreach ($history as $entry) {
            if ($entry['role'] === 'user' && $entry['content'] === $formattedMessage) {
                return true;
            }
        }
        return false;
    }

    private function sendDuplicateResponse()
    {
        echo "data: " . json_encode([
            'choices' => [
                ['delta' => [
                    'content' => "This question was already asked. Please try a different question.\n\n"
                ]]
            ]
        ]) . "\n\n";
        echo "data: [DONE]\n\n";
        ob_flush();
        flush();
    }
}

