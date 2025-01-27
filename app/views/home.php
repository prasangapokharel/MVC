<?php require __DIR__ . '/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raman's Hacker Motivation</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/hack-font/3.3.0/web/hack.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Hack', monospace;
            background-color: #0a0a0a;
        }
        .matrix-text {
            text-shadow: 0 0 5px #0f0;
        }
        .lighter-text {
            opacity: 0.7;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="container mx-auto px-4 py-16">
        <header class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl text-green-500 matrix-text font-bold mb-4">> RAMAN_</h1>
            <p class="text-green-400 text-xl md:text-2xl matrix-text">sudo motivation --force</p>
        </header>

        <main class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div class="bg-gray-900 p-6 rounded-lg border border-green-500">
                <h2 class="text-green-500 text-xl mb-4">> Code.Life()</h2>
                <p class="text-gray-300 lighter-text">Every line of code is a step towards mastery. Debug your life, optimize your potential.</p>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg border border-green-500">
                <h2 class="text-green-500 text-xl mb-4">> Mind.Hack()</h2>
                <p class="text-gray-300 lighter-text">Your mind is the most powerful computer. Upgrade it daily with new knowledge.</p>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg border border-green-500">
                <h2 class="text-green-500 text-xl mb-4">> Success.Execute()</h2>
                <p class="text-gray-300 lighter-text">While(!(succeed = try()));</p>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg border border-green-500">
                <h2 class="text-green-500 text-xl mb-4">> Innovation.Push()</h2>
                <p class="text-gray-300 lighter-text">Break the system. Create new paradigms. Revolutionize the world.</p>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg border border-green-500">
                <h2 class="text-green-500 text-xl mb-4">> Goals.Compile()</h2>
                <p class="text-gray-300 lighter-text">Transform your dreams into executable actions. Compile success.</p>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg border border-green-500">
                <h2 class="text-green-500 text-xl mb-4">> Future.Deploy()</h2>
                <p class="text-gray-300 lighter-text">The future belongs to those who code it. Deploy your potential now.</p>
            </div>
        </main>

        <footer class="text-center mt-16">
            <p class="text-green-500 matrix-text text-sm">/* Coded with determination by Raman */</p>
        </footer>
    </div>

    <script>
        // Matrix rain effect
        const canvas = document.createElement('canvas');
        document.body.appendChild(canvas);
        canvas.style.position = 'fixed';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.zIndex = '-1';
        
        const ctx = canvas.getContext('2d');
        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        const chars = '01'.split('');
        const drops = [];
        const fontSize = 14;
        const columns = width / fontSize;

        for(let i = 0; i < columns; i++) {
            drops[i] = 1;
        }

        function draw() {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
            ctx.fillRect(0, 0, width, height);
            ctx.fillStyle = '#0f0';
            ctx.font = fontSize + 'px Hack';

            for(let i = 0; i < drops.length; i++) {
                const text = chars[Math.floor(Math.random() * chars.length)];
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                if(drops[i] * fontSize > height && Math.random() > 0.975) {
                    drops[i] = 0;
                }
                drops[i]++;
            }
        }

        setInterval(draw, 33);
        window.addEventListener('resize', () => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        });
    </script>
</body>
</html>
<?php require __DIR__ . '/components/footer.php'; ?>

