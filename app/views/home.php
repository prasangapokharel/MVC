<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mosquito AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/php.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
        }
        
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-weight: 200;
        }

        .chat-container::-webkit-scrollbar {
            width: 6px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-container::-webkit-scrollbar-thumb {
            background: #333333;
            border-radius: 3px;
        }

        .chat-container::-webkit-scrollbar-thumb:hover {
            background: #444444;
        }

        .message {
            opacity: 0;
            transform: translateY(10px);
            animation: messageAppear 0.3s ease forwards;
        }

        @keyframes messageAppear {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .typing::after {
            content: '▋';
            animation: blink 1s infinite;
            margin-left: 2px;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        .code-block {
            position: relative;
            background: #1a1a1a;
            border-radius: 0.5rem;
            margin: 1rem 0;
            border: 1px solid #333;
        }

        .code-block pre {
            margin: 0;
            padding: 2.5rem 1rem 1rem;
            overflow-x: auto;
        }

        .code-block .copy-button {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.25rem 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s;
            opacity: 0;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: #fff;
        }

        .code-block:hover .copy-button {
            opacity: 1;
        }

        .code-block .copy-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .language-label {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            font-size: 0.75rem;
            color: #888888;
        }

        .input-area {
            background: linear-gradient(to right, #111111, #222222);
            border-top: 1px solid #333;
            padding: 1rem;
            position: relative;
        }

        .chat-input {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            min-height: 50px;
            max-height: 150px;
            padding-right: 6rem;
        }

        .chat-input:focus {
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2);
        }

        .input-buttons {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 0.5rem;
        }

        .send-button {
            transition: all 0.3s ease;
        }

        .send-button:hover {
            transform: scale(1.1);
        }

        .message-bubble {
            background: linear-gradient(145deg, #111111, #222222);
            box-shadow: 5px 5px 10px #0a0a0a, -5px -5px 10px #262626;
        }

        .user-avatar, .ai-avatar {
            background: linear-gradient(145deg, #333333, #222222);
            box-shadow: 2px 2px 5px #0a0a0a, -2px -2px 5px #262626;
        }

        .alert {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            animation: slideIn 0.3s ease-out;
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.9);
            color: white;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 640px) {
            .message-bubble {
                max-width: 80vw;
            }
            
            .chat-container {
                padding: 0.5rem;
            }
            
            .input-area {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">
    <div class="flex-1 flex flex-col bg-black">
        <div class="h-14 border-b border-gray-800 flex items-center justify-between px-4">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span class="text-sm text-gray-300">Mosquito AI is ready to assist</span>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-300 hover:text-white transition-colors">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="text-gray-300 hover:text-white transition-colors">
                    <i class="fas fa-user-circle"></i>
                </button>
            </div>
        </div>

        <div id="chatContainer" class="flex-1 overflow-y-auto chat-container">
            <div class="max-w-4xl mx-auto p-4 space-y-6">
                <!-- Messages will be added here -->
            </div>
        </div>

        <div class="input-area">
            <div class="max-w-4xl mx-auto relative">
                <form id="chatForm" class="relative">
                    <textarea 
                        id="messageInput"
                        class="chat-input w-full  text-white rounded-lg pl-4 pr-16 py-3 border border-gray-700 focus:outline-none focus:border-white focus:ring-1 focus:ring-white resize-none"
                        placeholder="Ask me anything... (Press Enter to send, Shift + Enter for new line)"
                    ></textarea>
                    <div class="input-buttons">
                        <button type="button" class="text-gray-400 hover:text-white transition-colors" id="clearButton">
                            <i class="fas fa-times"></i>
                        </button>
                        <button type="submit" class="send-button text-white hover:text-gray-300 transition-colors disabled:opacity-50">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="alertContainer"></div>

    <script>
    $(document).ready(function() {
        const chatContainer = $('#chatContainer');
        const messageInput = $('#messageInput');
        const chatForm = $('#chatForm');
        let currentResponse = '';
        let isGenerating = false;

        function showAlert(message, type = 'success') {
            const alert = $(`
                <div class="alert alert-${type}">
                    <i class="fas fa-check-circle"></i>
                    <span>${message}</span>
                </div>
            `);
            
            $('#alertContainer').append(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }

        marked.setOptions({
            renderer: new marked.Renderer(),
            highlight: function(code, language) {
                const validLanguage = hljs.getLanguage(language) ? language : 'plaintext';
                return hljs.highlight(validLanguage, code).value;
            },
            pedantic: false,
            gfm: true,
            breaks: true,
            sanitize: false,
            smartLists: true,
            smartypants: false,
            xhtml: false
        });

        addMessage("Hello! Welcome to Mosquito AI. How can I assist you today?", false);

        function formatMarkdown(content) {
            try {
                content = content.replace(/```(\w+)?\s*([\s\S]*?)```/g, function(match, language, code) {
                    language = language || 'plaintext';
                    return `<div class="code-block">
                        <span class="language-label">${language}</span>
                        <button class="copy-button" onclick="copyCode(this)">
                            <i class="fas fa-copy"></i>
                            <span>Copy</span>
                        </button>
                        <pre><code class="language-${language}">${code.trim()}</code></pre>
                    </div>`;
                });

                return marked.parse(content);
            } catch (e) {
                console.error('Error formatting markdown:', e);
                return content;
            }
        }

        function addMessage(content, isUser = false) {
            const formattedContent = isUser ? content : formatMarkdown(content);
            const messageHtml = `
                <div class="message flex items-start space-x-3 ${isUser ? 'justify-end' : ''}">
                    ${isUser ? '' : `
                    <div class="flex-shrink-0 w-8 h-8 rounded-full ai-avatar flex items-center justify-center">
                        <img src="/images/logo.svg" alt="AI">
                    </div>
                    `}
                    <div class="flex-1 overflow-hidden ${isUser ? 'text-right' : ''}">
                        <div class="message-bubble inline-block rounded-lg p-4 text-white ${isUser ? 'bg-gray-700' : 'bg-gray-800'} markdown-content">
                            ${formattedContent}
                        </div>
                    </div>
                    ${isUser ? `
                    <div class="flex-shrink-0 w-8 h-8 rounded-full user-avatar flex items-center justify-center">
                        <i class="fas fa-user text-sm text-white"></i>
                    </div>
                    ` : ''}
                </div>
            `;
            
            const messagesContainer = chatContainer.find('.max-w-4xl');
            messagesContainer.append(messageHtml);
            scrollToBottom();
            hljs.highlightAll();
        }

        function addStreamingMessage() {
            const messageHtml = `
                <div class="message streaming-message flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full ai-avatar flex items-center justify-center">
                        <img src="/images/logo.svg" alt="AI">
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <div class="message-bubble inline-block rounded-lg p-4 text-white bg-gray-800 markdown-content">
                            <div class="flex items-center space-x-2 text-white">
                                <img src="/images/logo.svg" alt="Mosquito AI" class="w-4 h-4">
                                <span class="streaming-content typing">Mosquito AI is thinking...</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            const messagesContainer = chatContainer.find('.max-w-4xl');
            messagesContainer.append(messageHtml);
            scrollToBottom();
        }

        function updateStreamingContent(content) {
            $('.streaming-content').html(formatMarkdown(content));
            scrollToBottom();
            hljs.highlightAll();
        }

        function finishStreaming() {
            $('.streaming-content').removeClass('typing');
            $('.streaming-message').removeClass('streaming-message');
            currentResponse = '';
        }

        function scrollToBottom() {
            chatContainer.scrollTop(chatContainer[0].scrollHeight);
        }

        window.copyCode = function(button) {
            const codeBlock = button.closest('.code-block');
            const code = codeBlock.querySelector('code').textContent;
            
            navigator.clipboard.writeText(code).then(() => {
                const icon = button.querySelector('i');
                const span = button.querySelector('span');
                icon.className = 'fas fa-check';
                span.textContent = 'Copied!';
                showAlert('Code copied to clipboard!');
                
                setTimeout(() => {
                    icon.className = 'fas fa-copy';
                    span.textContent = 'Copy';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                showAlert('Failed to copy code', 'error');
            });
        }

        $('#clearButton').on('click', function() {
            messageInput.val('').trigger('input');
            messageInput.focus();
        });

        messageInput.on('input', function() {
            this.style.height = 'auto';
            const newHeight = Math.min(this.scrollHeight, 150);
            this.style.height = newHeight + 'px';
            
            const inputHeight = $(this).outerHeight();
            $('.input-buttons').css('top', inputHeight / 2);
        });

        chatForm.on('submit', function(e) {
            e.preventDefault();
            if (isGenerating) return;

            const message = messageInput.val().trim();
            if (!message) return;

            addMessage(message, true);
            messageInput.val('').trigger('input');
            addStreamingMessage();
            isGenerating = true;

            $.ajax({
                url: window.location.href,
                method: 'POST',
                data: { message: message },
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                xhrFields: {
                    onprogress: function(e) {
                        const response = e.currentTarget.response;
                        const lines = response.split('\n');
                        
                        lines.forEach(line => {
                            if (line.startsWith('data: ')) {
                                const data = line.substring(6);
                                if (data === '[DONE]') {
                                    finishStreaming();
                                    isGenerating = false;
                                    return;
                                }
                                
                                try {
                                    const parsed = JSON.parse(data);
                                    if (parsed.choices && parsed.choices[0].delta && parsed.choices[0].delta.content) {
                                        currentResponse += parsed.choices[0].delta.content;
                                        updateStreamingContent(currentResponse);
                                    }
                                } catch (err) {
                                    console.error('Error parsing JSON:', err);
                                }
                            }
                        });
                    }
                },
                error: function() {
                    updateStreamingContent('Sorry, there was an error processing your request.');
                    finishStreaming();
                    isGenerating = false;
                }
            });
        });

        messageInput.on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.submit();
            }
        });
    });
    </script>
</body>
</html>