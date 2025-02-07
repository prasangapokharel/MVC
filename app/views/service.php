<?php require __DIR__ . '/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Future Tech</title>
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        .pulsing {
            animation: pulse 3s ease-in-out infinite;
        }

        .rotating {
            animation: rotate 20s linear infinite;
        }

        .hexagon {
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        }

        .cyber-border {
            border: 2px solid transparent;
            background: linear-gradient(45deg, #00f7ff, #ff00e4);
            background-clip: padding-box;
            position: relative;
        }

        .cyber-border::before {
            content: '';
            position: absolute;
            top: -2px; right: -2px; bottom: -2px; left: -2px;
            background: linear-gradient(45deg, #00f7ff, #ff00e4);
            z-index: -1;
            border-radius: inherit;
        }

        .glitch {
            position: relative;
        }

        .glitch::before, .glitch::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .glitch::before {
            animation: glitch-anim 2s infinite linear alternate-reverse;
            clip: rect(44px, 450px, 56px, 0);
            text-shadow: -2px 0 #ff00c1;
        }

        .glitch::after {
            animation: glitch-anim 4s infinite linear alternate-reverse;
            clip: rect(44px, 450px, 56px, 0);
            text-shadow: 2px 0 #00fff9;
        }

        .portal {
            perspective: 1000px;
        }

        .portal-content {
            transform-style: preserve-3d;
            animation: portal-spin 20s linear infinite;
        }

        @keyframes portal-spin {
            from { transform: rotateY(0deg); }
            to { transform: rotateY(360deg); }
        }

        .cyber-grid {
            background-image: 
                linear-gradient(transparent 97%, #00fff9 97%),
                linear-gradient(90deg, transparent 97%, #00fff9 97%);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">
    <!-- Hero Section -->
    <section class="gradient-bg min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 cyber-grid opacity-20"></div>
        <div class="container mx-auto px-4 z-10">
            <h1 class="text-6xl md:text-8xl font-bold text-center glitch mb-8" data-text="FUTURE NOW">FUTURE NOW</h1>
            <p class="text-xl md:text-2xl text-center text-blue-200 floating">Experience Tomorrow's Technology Today</p>
            <div class="mt-12 flex justify-center">
                <button class="cyber-border px-8 py-4 text-xl rounded-lg pulsing hover:scale-105 transition-transform">
                    Explore Universe
                </button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 relative">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-900 p-8 rounded-xl cyber-border floating" style="animation-delay: 0s;">
                    <div class="hexagon bg-blue-500 w-16 h-16 mb-6 rotating"></div>
                    <h3 class="text-2xl font-bold mb-4">Quantum Computing</h3>
                    <p class="text-gray-400">Harness the power of quantum superposition for unlimited possibilities.</p>
                </div>
                <div class="bg-gray-900 p-8 rounded-xl cyber-border floating" style="animation-delay: 0.2s;">
                    <div class="hexagon bg-purple-500 w-16 h-16 mb-6 rotating"></div>
                    <h3 class="text-2xl font-bold mb-4">Neural Interface</h3>
                    <p class="text-gray-400">Direct brain-computer interfaces for seamless integration.</p>
                </div>
                <div class="bg-gray-900 p-8 rounded-xl cyber-border floating" style="animation-delay: 0.4s;">
                    <div class="hexagon bg-pink-500 w-16 h-16 mb-6 rotating"></div>
                    <h3 class="text-2xl font-bold mb-4">Holographic Display</h3>
                    <p class="text-gray-400">Immersive 3D projections that redefine visual experience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portal Section -->
    <section class="py-20 portal">
        <div class="container mx-auto px-4">
            <div class="portal-content bg-gray-900 rounded-2xl p-12 cyber-border">
                <h2 class="text-4xl md:text-6xl font-bold text-center mb-8">Enter The Portal</h2>
                <div class="max-w-3xl mx-auto text-center">
                    <p class="text-xl text-gray-400 mb-8">Step into a world where imagination meets reality. Where every dream becomes a digital experience.</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="pulsing p-4">
                            <div class="text-4xl font-bold text-blue-400">100+</div>
                            <div class="text-sm text-gray-400">Virtual Worlds</div>
                        </div>
                        <div class="pulsing p-4" style="animation-delay: 0.2s;">
                            <div class="text-4xl font-bold text-purple-400">50K+</div>
                            <div class="text-sm text-gray-400">Users</div>
                        </div>
                        <div class="pulsing p-4" style="animation-delay: 0.4s;">
                            <div class="text-4xl font-bold text-pink-400">1M+</div>
                            <div class="text-sm text-gray-400">Experiences</div>
                        </div>
                        <div class="pulsing p-4" style="animation-delay: 0.6s;">
                            <div class="text-4xl font-bold text-green-400">24/7</div>
                            <div class="text-sm text-gray-400">Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

 
</body>
</html>
<?php require __DIR__ . '/components/footer.php'; ?>

