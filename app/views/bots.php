<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trading Bot Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        .grid-bg {
            background: linear-gradient(to bottom, #000000, #111827);
            background-image: 
                linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.9)),
                url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1h18v18H1V1zm1 1v16h16V2H2z' fill='%23333' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .glass-card {
            background: rgba(17, 25, 40, 0.75);
            backdrop-filter: blur(16px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.125);
        }

        .glow-blue {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-black text-white grid-bg min-h-screen">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center hidden">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
            <p class="text-blue-500">Connecting to Exchange...</p>
        </div>
    </div>

    <!-- Login Form -->
    <div id="loginForm" class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto glass-card p-8">
            <div class="flex items-center justify-center mb-8">
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-2 text-center">Trading Bot</h2>
            <p class="text-gray-400 text-center mb-6">Connect your exchange account to start trading</p>
            <form id="botForm" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">API Key</label>
                    <input type="text" name="api_key" class="w-full bg-black/50 border border-gray-700 rounded-lg p-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">API Secret</label>
                    <input type="password" name="api_secret" class="w-full bg-black/50 border border-gray-700 rounded-lg p-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">API Passphrase</label>
                    <input type="password" name="api_passphrase" class="w-full bg-black/50 border border-gray-700 rounded-lg p-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-150 ease-in-out glow-blue">
                    CONNECT BOT
                </button>
            </form>
        </div>
    </div>

    <!-- Trading Dashboard -->
    <div id="tradingDashboard" class="hidden">
        <!-- Navigation -->
        <nav class="border-b border-gray-800 bg-black/50 backdrop-blur-md sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <div class="text-xl font-semibold text-blue-500">Trading Bot</div>
                        <span id="connectionStatus" class="flex items-center text-sm">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse-slow"></span>
                            Active
                        </span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div id="accountBalance" class="text-sm bg-blue-500/10 px-4 py-2 rounded-full">
                            Balance: <span class="text-blue-400">Loading...</span>
                        </div>
                        <button id="stopBotBtn" class="text-sm text-red-400 hover:text-red-300">Stop Bot</button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <!-- Trading Controls -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Trading Pair Selection -->
                <div class="glass-card p-6">
                    <h3 class="text-gray-400 text-sm mb-2">Trading Pair</h3>
                    <select id="tradingPair" class="w-full bg-black/50 border border-gray-700 rounded-lg p-2 text-white">
                        <option value="DOGE-USDT">DOGE/USDT</option>
                        <option value="BTC-USDT">BTC/USDT</option>
                        <option value="ETH-USDT">ETH/USDT</option>
                    </select>
                </div>

                <!-- Current Price -->
                <div class="glass-card p-6">
                    <h3 class="text-gray-400 text-sm mb-2">Current Price</h3>
                    <div class="flex items-baseline">
                        <div id="currentPrice" class="text-2xl font-bold text-white">0.00</div>
                        <div id="priceChange" class="ml-2 text-sm">0.00%</div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Last updated: <span id="lastUpdate">-</span></div>
                </div>

                <!-- Bot Status -->
                <div class="glass-card p-6">
                    <h3 class="text-gray-400 text-sm mb-2">Bot Status</h3>
                    <div id="botStatus" class="text-lg font-semibold text-green-500">Running</div>
                    <div class="text-xs text-gray-500 mt-1">Trades today: <span id="tradesCount">0</span></div>
                </div>

                <!-- Current PNL -->
                <div class="glass-card p-6">
                    <h3 class="text-gray-400 text-sm mb-2">Today's PNL</h3>
                    <div id="dailyPnl" class="text-2xl font-bold text-white">0.00%</div>
                    <div class="text-xs text-gray-500 mt-1">Total profit: <span id="totalProfit">$0.00</span></div>
                </div>
            </div>

            <!-- Active Positions -->
            <div class="glass-card p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Active Positions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-gray-400 text-sm">
                                <th class="text-left py-2">Symbol</th>
                                <th class="text-left py-2">Side</th>
                                <th class="text-left py-2">Entry Price</th>
                                <th class="text-left py-2">Current Price</th>
                                <th class="text-left py-2">Size</th>
                                <th class="text-left py-2">PNL</th>
                            </tr>
                        </thead>
                        <tbody id="positionsTable"></tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Trades -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Recent Trades</h3>
                    <button id="refreshTrades" class="text-blue-500 hover:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-gray-400 text-sm">
                                <th class="text-left py-2">Time</th>
                                <th class="text-left py-2">Symbol</th>
                                <th class="text-left py-2">Type</th>
                                <th class="text-left py-2">Side</th>
                                <th class="text-left py-2">Price</th>
                                <th class="text-left py-2">Amount</th>
                                <th class="text-left py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody id="tradesTable"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Error Toast -->
    <div id="errorToast" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg hidden">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span id="errorMessage">Error message here</span>
        </div>
    </div>

    // Replace your existing script section with this:
<script>
    let websocket = null;
    let lastPrice = 0;
    let isAuthenticated = false;
    let tradesCount = 0;
    let totalProfit = 0;

    // Initialize WebSocket connection
    function initWebSocket() {
        const wsUrl = `wss://ws.okx.com:8443/ws/v5/public`;
        websocket = new WebSocket(wsUrl);

        websocket.onopen = () => {
            console.log('WebSocket Connected');
            const tradingPair = document.getElementById('tradingPair').value;
            subscribeToTicker(tradingPair);
        };

        websocket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (data.data && data.data[0]) {
                updateTickerData(data.data[0]);
            }
        };

        websocket.onerror = (error) => {
            showError('WebSocket connection error');
        };

        websocket.onclose = () => {
            console.log('WebSocket Disconnected');
            setTimeout(initWebSocket, 5000);
        };
    }

    // Form submission handler
    document.getElementById('botForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Show loading overlay
        document.getElementById('loadingOverlay').classList.remove('hidden');
        
        try {
            // Get form data
            const formData = new FormData(this);
            
            // Make the API request
            const response = await fetch('BotController.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            let data;
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                data = await response.json();
            } else {
                const text = await response.text();
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid response format from server');
                }
            }

            if (data.status === 'success') {
                // Store credentials in session storage (encrypted)
                const credentials = {
                    api_key: formData.get('api_key'),
                    api_secret: formData.get('api_secret'),
                    api_passphrase: formData.get('api_passphrase')
                };
                sessionStorage.setItem('bot_credentials', btoa(JSON.stringify(credentials)));
                
                // Hide login form and show dashboard
                document.getElementById('loginForm').classList.add('hidden');
                document.getElementById('tradingDashboard').classList.remove('hidden');
                
                // Initialize dashboard
                isAuthenticated = true;
                initializeDashboard();
                
                // Update initial balance if provided
                if (data.balance) {
                    document.getElementById('accountBalance').innerHTML = 
                        `Balance: <span class="text-blue-400">$${parseFloat(data.balance).toFixed(2)}</span>`;
                }
            } else {
                throw new Error(data.message || 'Connection failed');
            }
        } catch (error) {
            showError(error.message);
        } finally {
            // Hide loading overlay
            document.getElementById('loadingOverlay').classList.add('hidden');
        }
    });

    // API request helper function
    async function makeApiRequest(endpoint, method = 'GET', body = null) {
        try {
            const credentials = sessionStorage.getItem('bot_credentials');
            if (!credentials) {
                throw new Error('Bot not authenticated');
            }

            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                }
            };

            if (body) {
                options.body = JSON.stringify(body);
            }

            const response = await fetch(`BotController.php?action=${endpoint}`, options);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            return data;
        } catch (error) {
            showError(error.message);
            return null;
        }
    }

    // Fetch Account Balance
    async function fetchAccountBalance() {
        const data = await makeApiRequest('getStatus');
        if (data && data.data && data.data.balance) {
            document.getElementById('accountBalance').innerHTML = 
                `Balance: <span class="text-blue-400">$${parseFloat(data.data.balance).toFixed(2)}</span>`;
        }
    }

    // Fetch Active Positions
    async function fetchPositions() {
        const data = await makeApiRequest('getPositions');
        if (data && data.positions) {
            updatePositionsTable(data.positions);
        }
    }

    // Update Positions Table
    function updatePositionsTable(positions) {
        const positionsHTML = positions.map(position => `
            <tr class="border-t border-gray-800">
                <td class="py-2">${position.instId}</td>
                <td class="py-2 ${position.posSide === 'long' ? 'text-green-500' : 'text-red-500'}">
                    ${position.posSide.toUpperCase()}
                </td>
                <td class="py-2">$${parseFloat(position.avgPx).toFixed(6)}</td>
                <td class="py-2">$${parseFloat(position.last).toFixed(6)}</td>
                <td class="py-2">${parseFloat(position.pos).toFixed(4)}</td>
                <td class="py-2 ${parseFloat(position.upl) >= 0 ? 'text-green-500' : 'text-red-500'}">
                    ${parseFloat(position.upl).toFixed(2)}%
                </td>
            </tr>
        `).join('');

        document.getElementById('positionsTable').innerHTML = positionsHTML || `
            <tr class="border-t border-gray-800">
                <td colspan="6" class="py-4 text-center text-gray-500">No active positions</td>
            </tr>
        `;
    }

    // Fetch Recent Trades
    async function fetchRecentTrades() {
        const data = await makeApiRequest('getTrades');
        if (data && data.trades) {
            updateTradesTable(data.trades);
        }
    }

    // Update Trades Table
    function updateTradesTable(trades) {
        const tradesHTML = trades.map(trade => `
            <tr class="border-t border-gray-800">
                <td class="py-2">${new Date(parseInt(trade.ts)).toLocaleTimeString()}</td>
                <td class="py-2">${trade.instId}</td>
                <td class="py-2">${trade.ordType}</td>
                <td class="py-2 ${trade.side === 'buy' ? 'text-green-500' : 'text-red-500'}">
                    ${trade.side.toUpperCase()}
                </td>
                <td class="py-2">$${parseFloat(trade.px).toFixed(6)}</td>
                <td class="py-2">${parseFloat(trade.sz).toFixed(4)}</td>
                <td class="py-2">$${(parseFloat(trade.px) * parseFloat(trade.sz)).toFixed(2)}</td>
            </tr>
        `).join('');

        document.getElementById('tradesTable').innerHTML = tradesHTML || `
            <tr class="border-t border-gray-800">
                <td colspan="7" class="py-4 text-center text-gray-500">No recent trades</td>
            </tr>
        `;

        // Update trades count and PNL
        updateTradeStats(trades);
    }

    // Update Trade Statistics
    function updateTradeStats(trades) {
        tradesCount = trades.length;
        document.getElementById('tradesCount').textContent = tradesCount;

        // Calculate daily PNL
        const today = new Date().setHours(0, 0, 0, 0);
        const todayTrades = trades.filter(trade => 
            parseInt(trade.ts) >= today
        );

        const dailyPnl = todayTrades.reduce((total, trade) => 
            total + (trade.pnl ? parseFloat(trade.pnl) : 0), 0
        );

        document.getElementById('dailyPnl').textContent = `${dailyPnl.toFixed(2)}%`;
        document.getElementById('dailyPnl').className = 
            `text-2xl font-bold ${dailyPnl >= 0 ? 'text-green-500' : 'text-red-500'}`;

        // Update total profit
        totalProfit = trades.reduce((total, trade) => 
            total + (trade.profit ? parseFloat(trade.profit) : 0), 0
        );
        document.getElementById('totalProfit').textContent = `$${totalProfit.toFixed(2)}`;
    }

    // Show Error Toast
    function showError(message) {
        const toast = document.getElementById('errorToast');
        document.getElementById('errorMessage').textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 5000);
    }

    // Initialize Dashboard
    function initializeDashboard() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
        
        Promise.all([
            fetchAccountBalance(),
            fetchPositions(),
            fetchRecentTrades()
        ]).then(() => {
            initWebSocket();
            document.getElementById('loadingOverlay').classList.add('hidden');
            
            // Start periodic updates
            setInterval(fetchAccountBalance, 10000);  // Every 10 seconds
            setInterval(fetchPositions, 5000);       // Every 5 seconds
            setInterval(fetchRecentTrades, 5000);    // Every 5 seconds
        }).catch(error => {
            showError('Error initializing dashboard');
            document.getElementById('loadingOverlay').classList.add('hidden');
        });
    }

    // Event Listeners
    document.getElementById('tradingPair').addEventListener('change', function() {
        if (websocket) {
            const tradingPair = this.value;
            subscribeToTicker(tradingPair);
            fetchPositions();
            fetchRecentTrades();
        }
    });

    document.getElementById('refreshTrades').addEventListener('click', fetchRecentTrades);

    document.getElementById('stopBotBtn').addEventListener('click', async function() {
        try {
            const data = await makeApiRequest('stopBot', 'POST');
            if (data && data.status === 'success') {
                if (websocket) {
                    websocket.close();
                }
                sessionStorage.removeItem('bot_credentials');
                location.reload();
            }
        } catch (error) {
            showError('Error stopping bot');
        }
    });

    // Handle page unload
    window.addEventListener('beforeunload', function(event) {
        if (isAuthenticated) {
            event.preventDefault();
            event.returnValue = 'Are you sure you want to leave? The bot will be stopped.';
        }
    });
</script>
</body>
</html>