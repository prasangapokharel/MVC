<?php

use Godsu\Mvc\Controllers\HomeController;
use Godsu\Mvc\Controllers\BotController;

return [
    '/' => [HomeController::class, 'index'],
    'bots' => [BotController::class, 'index'],
    'bot' => [BotController::class, 'index'],
    'bot/start' => [BotController::class, 'startBot', 'POST'],
    'bot/execute' => [BotController::class, 'executeTrading', 'POST'],
    'bot/status' => [BotController::class, 'getStatus', 'GET'],
    'bot/stop' => [BotController::class, 'stopBot', 'POST']
];