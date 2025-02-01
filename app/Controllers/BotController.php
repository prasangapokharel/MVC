<?php

namespace Godsu\Mvc\Controllers;

use Godsu\Mvc\Models\BotModel;
use Exception;

class BotController {
    private $botModel;
    private $tradingEnabled = false;

    public function __construct() {
        $this->botModel = new BotModel();
        session_start();
    }

    public function index() {
        require __DIR__ . '/../views/bots.php';
    }

    public function startBot() {
        try {
            $apiKey = $_POST['api_key'] ?? '';
            $apiSecret = $_POST['api_secret'] ?? '';
            $apiPassphrase = $_POST['api_passphrase'] ?? '';

            if (empty($apiKey) || empty($apiSecret) || empty($apiPassphrase)) {
                throw new Exception("Please provide all API credentials");
            }

            $this->botModel->setCredentials($apiKey, $apiSecret, $apiPassphrase);
            
            // Verify credentials by checking account balance
            $balanceCheck = $this->botModel->getAccountBalance();
            if ($balanceCheck['status'] === 'error') {
                throw new Exception("Invalid API credentials");
            }

            $_SESSION['bot_credentials'] = [
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'api_passphrase' => $apiPassphrase
            ];

            $this->tradingEnabled = true;
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Bot successfully connected',
                'balance' => $balanceCheck['balance']
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function executeTrading() {
        try {
            if (!isset($_SESSION['bot_credentials'])) {
                throw new Exception("Bot not authenticated");
            }

            $credentials = $_SESSION['bot_credentials'];
            $this->botModel->setCredentials(
                $credentials['api_key'],
                $credentials['api_secret'],
                $credentials['api_passphrase']
            );

            $result = $this->botModel->executeStrategy();
            
            if ($result['status'] === 'error') {
                throw new Exception($result['message']);
            }

            // Get updated account information
            $balance = $this->botModel->getAccountBalance();
            $positions = $this->botModel->getPositions();
            $trades = $this->botModel->getTradeHistory();

            echo json_encode([
                'status' => 'success',
                'trading_result' => $result,
                'balance' => $balance['status'] === 'success' ? $balance['balance'] : null,
                'positions' => $positions['status'] === 'success' ? $positions['positions'] : [],
                'trades' => $trades['status'] === 'success' ? $trades['trades'] : []
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getStatus() {
        try {
            if (!isset($_SESSION['bot_credentials'])) {
                throw new Exception("Bot not authenticated");
            }

            $credentials = $_SESSION['bot_credentials'];
            $this->botModel->setCredentials(
                $credentials['api_key'],
                $credentials['api_secret'],
                $credentials['api_passphrase']
            );

            $balance = $this->botModel->getAccountBalance();
            $positions = $this->botModel->getPositions();
            $trades = $this->botModel->getTradeHistory();
            $currentPrice = $this->botModel->getCurrentPrice();

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'balance' => $balance['status'] === 'success' ? $balance['balance'] : null,
                    'positions' => $positions['status'] === 'success' ? $positions['positions'] : [],
                    'trades' => $trades['status'] === 'success' ? $trades['trades'] : [],
                    'current_price' => $currentPrice['status'] === 'success' ? $currentPrice['price'] : null
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function stopBot() {
        try {
            if (!isset($_SESSION['bot_credentials'])) {
                throw new Exception("Bot not authenticated");
            }

            // Close any open positions before stopping
            $credentials = $_SESSION['bot_credentials'];
            $this->botModel->setCredentials(
                $credentials['api_key'],
                $credentials['api_secret'],
                $credentials['api_passphrase']
            );

            $positions = $this->botModel->getPositions();
            if ($positions['status'] === 'success' && !empty($positions['positions'])) {
                foreach ($positions['positions'] as $position) {
                    if ($position['pos'] !== '0') {
                        $this->botModel->placeSellOrder(floatval($position['markPx']));
                    }
                }
            }

            // Clear session
            unset($_SESSION['bot_credentials']);
            session_destroy();

            echo json_encode([
                'status' => 'success',
                'message' => 'Bot stopped successfully'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}