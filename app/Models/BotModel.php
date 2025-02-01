<?php

namespace Godsu\Mvc\Models;

use GuzzleHttp\Client;
use Exception;

class BotModel {
    private $client;
    private $apiKey;
    private $apiSecret;
    private $apiPassphrase;
    private $apiBaseUrl = "https://www.okx.com";
    private $tradingPair = "DOGE-USDT";
    private $tradeAmount = 100; // USDT
    private $profitTarget = 1.5; // 1.5%
    private $stopLoss = 1.0; // 1.0%
    private $lastBuyPrice = 0;
    private $inPosition = false;

    public function __construct() {
        $this->client = new Client(['timeout' => 30]);
    }

    public function setCredentials($apiKey, $apiSecret, $apiPassphrase) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->apiPassphrase = $apiPassphrase;
    }

    private function generateSignature($timestamp, $method, $requestPath, $body = '') {
        $message = $timestamp . $method . $requestPath . $body;
        return base64_encode(hash_hmac('sha256', $message, $this->apiSecret, true));
    }

    private function sendRequest($endpoint, $method = 'GET', $params = []) {
        $timestamp = time() * 1000;
        $body = $method === 'GET' ? '' : json_encode($params);
        $signature = $this->generateSignature($timestamp, $method, $endpoint, $body);

        try {
            $headers = [
                'OK-ACCESS-KEY' => $this->apiKey,
                'OK-ACCESS-SIGN' => $signature,
                'OK-ACCESS-TIMESTAMP' => $timestamp,
                'OK-ACCESS-PASSPHRASE' => $this->apiPassphrase,
                'Content-Type' => 'application/json'
            ];

            $response = $this->client->request($method, $this->apiBaseUrl . $endpoint, [
                'headers' => $headers,
                'body' => $body
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            throw new Exception("API request failed: " . $e->getMessage());
        }
    }

    public function getAccountBalance() {
        try {
            $response = $this->sendRequest('/api/v5/account/balance');
            if (isset($response['data'][0]['totalEq'])) {
                return [
                    'status' => 'success',
                    'balance' => $response['data'][0]['totalEq']
                ];
            }
            throw new Exception("Invalid balance response");
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getCurrentPrice() {
        try {
            $response = $this->sendRequest("/api/v5/market/ticker?instId={$this->tradingPair}");
            if (isset($response['data'][0]['last'])) {
                return [
                    'status' => 'success',
                    'price' => $response['data'][0]['last']
                ];
            }
            throw new Exception("Invalid price response");
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function placeBuyOrder($price) {
        try {
            $size = $this->tradeAmount / $price;
            $params = [
                'instId' => $this->tradingPair,
                'tdMode' => 'cash',
                'side' => 'buy',
                'ordType' => 'market',
                'sz' => number_format($size, 8, '.', ''),
            ];

            $response = $this->sendRequest('/api/v5/trade/order', 'POST', $params);
            
            if ($response['code'] === '0') {
                $this->lastBuyPrice = $price;
                $this->inPosition = true;
                return [
                    'status' => 'success',
                    'orderId' => $response['data'][0]['ordId']
                ];
            }
            throw new Exception("Order placement failed");
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function placeSellOrder($price) {
        try {
            $size = $this->tradeAmount / $this->lastBuyPrice;
            $params = [
                'instId' => $this->tradingPair,
                'tdMode' => 'cash',
                'side' => 'sell',
                'ordType' => 'market',
                'sz' => number_format($size, 8, '.', ''),
            ];

            $response = $this->sendRequest('/api/v5/trade/order', 'POST', $params);
            
            if ($response['code'] === '0') {
                $this->inPosition = false;
                return [
                    'status' => 'success',
                    'orderId' => $response['data'][0]['ordId']
                ];
            }
            throw new Exception("Order placement failed");
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getOrderStatus($orderId) {
        try {
            $response = $this->sendRequest("/api/v5/trade/order?ordId={$orderId}&instId={$this->tradingPair}");
            if (isset($response['data'][0])) {
                return [
                    'status' => 'success',
                    'orderStatus' => $response['data'][0]['state']
                ];
            }
            throw new Exception("Invalid order status response");
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function executeStrategy() {
        try {
            $priceData = $this->getCurrentPrice();
            if ($priceData['status'] === 'error') {
                throw new Exception($priceData['message']);
            }

            $currentPrice = floatval($priceData['price']);
            $result = ['status' => 'success', 'action' => 'none'];

            if (!$this->inPosition) {
                // Check buy conditions (example: simple moving average)
                $buySignal = $this->checkBuySignal();
                if ($buySignal) {
                    $buyOrder = $this->placeBuyOrder($currentPrice);
                    if ($buyOrder['status'] === 'success') {
                        $result['action'] = 'buy';
                        $result['price'] = $currentPrice;
                        $result['orderId'] = $buyOrder['orderId'];
                    }
                }
            } else {
                // Check sell conditions
                $profitPrice = $this->lastBuyPrice * (1 + $this->profitTarget / 100);
                $stopLossPrice = $this->lastBuyPrice * (1 - $this->stopLoss / 100);

                if ($currentPrice >= $profitPrice || $currentPrice <= $stopLossPrice) {
                    $sellOrder = $this->placeSellOrder($currentPrice);
                    if ($sellOrder['status'] === 'success') {
                        $result['action'] = 'sell';
                        $result['price'] = $currentPrice;
                        $result['orderId'] = $sellOrder['orderId'];
                        $result['pnl'] = (($currentPrice - $this->lastBuyPrice) / $this->lastBuyPrice) * 100;
                    }
                }
            }

            return $result;

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function checkBuySignal() {
        try {
            // Get historical candles for analysis
            $response = $this->sendRequest("/api/v5/market/candles?instId={$this->tradingPair}&bar=1m&limit=20");
            if (!isset($response['data'])) {
                return false;
            }

            $closes = array_map(function($candle) {
                return floatval($candle[4]);
            }, $response['data']);

            // Calculate 5-period and 20-period moving averages
            $ma5 = array_sum(array_slice($closes, 0, 5)) / 5;
            $ma20 = array_sum($closes) / 20;

            // Buy signal: 5-period MA crosses above 20-period MA
            return $ma5 > $ma20;

        } catch (Exception $e) {
            return false;
        }
    }

    public function getTradeHistory() {
        try {
            $response = $this->sendRequest("/api/v5/trade/orders-history?instId={$this->tradingPair}&limit=50");
            if (isset($response['data'])) {
                return [
                    'status' => 'success',
                    'trades' => $response['data']
                ];
            }
            throw new Exception("Invalid trade history response");
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getPositions() {
        try {
            $response = $this->sendRequest("/api/v5/account/positions");
            if (isset($response['data'])) {
                return [
                    'status' => 'success',
                    'positions' => $response['data']
                ];
            }
            throw new Exception("Invalid positions response");
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}