<div class="sidebar w-72 bg-black border-r border-gray-800 flex flex-col transition-all duration-300 ease-in-out h-screen">
    <!-- Sidebar Header -->
    <div class="p-6 border-b border-gray-800">
        <div class="flex items-center space-x-3">
            <div class="relative w-10 h-10">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9b/Flag_of_Nepal.svg" 
                     alt="Nepal Flag" 
                     class="w-full h-full object-contain nepal-flag float-animation">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-blue-500/20 rounded-full blur-xl"></div>
            </div>
            <div>
                <h1 class="text-xl font-bold bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">
                    Nepal AI
                </h1>
                <p class="text-xs text-gray-400">Powered by OpenAI</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto custom-scrollbar">
        <!-- New Chat Button -->
        <button onclick="startNewChat()" 
                class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300
                       bg-gradient-to-r from-purple-500/10 to-blue-500/10 hover:from-purple-500/20 hover:to-blue-500/20
                       border border-gray-800 hover:border-gray-700 group">
            <i class="fas fa-plus-circle text-blue-400 group-hover:scale-110 transition-transform duration-300"></i>
            <span class="text-gray-300 group-hover:text-white">New Chat</span>
        </button>

        <!-- Chat History -->
        <div class="space-y-2 mt-6">
            <div class="px-4 text-xs font-medium text-gray-400 uppercase">Recent Chats</div>
            <div id="chatHistory" class="space-y-1">
                <!-- Chat history items will be added here via JavaScript -->
            </div>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="text-sm">
                    <div class="text-gray-300">Guest User</div>
                    <div class="text-xs text-gray-500">Free Plan</div>
                </div>
            </div>
            <button onclick="toggleSettings()" 
                    class="p-2 rounded-lg hover:bg-gray-800 transition-colors duration-300">
                <i class="fas fa-cog text-gray-400 hover:text-white"></i>
            </button>
        </div>

        <!-- Settings Menu (hidden by default) -->
        <div id="settingsMenu" class="hidden space-y-2">
            <button class="sidebar-button w-full">
                <i class="fas fa-palette"></i>
                <span>Theme</span>
            </button>
            <button class="sidebar-button w-full">
                <i class="fas fa-language"></i>
                <span>Language</span>
            </button>
            <button class="sidebar-button w-full">
                <i class="fas fa-question-circle"></i>
                <span>Help & FAQ</span>
            </button>
        </div>
    </div>
</div>

<style>
.sidebar-button {
    @apply flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-400 hover:text-white
           hover:bg-gray-800 transition-all duration-300;
}

.nepal-flag {
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.2));
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(-3deg); }
    50% { transform: translateY(-10px) rotate(3deg); }
}

.float-animation {
    animation: float 3s ease-in-out infinite;
}

/* Custom Scrollbar for Sidebar */
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #374151;
    border-radius: 20px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #4B5563;
}
</style>

<script>
// Sidebar functionality
function startNewChat() {
    // Clear chat container
    $('#chatContainer .max-w-4xl').empty();
    
    // Add initial message
    addMessage("नमस्ते! म तपाईंलाई कसरी सहयोग गर्न सक्छु?", false);
    
    // Add to chat history
    addChatToHistory("New Chat " + new Date().toLocaleTimeString());
}

function addChatToHistory(title) {
    const chatItem = `
        <button class="sidebar-button w-full text-left group">
            <i class="fas fa-message"></i>
            <span class="flex-1 truncate">${title}</span>
            <span class="opacity-0 group-hover:opacity-100 text-xs text-gray-500">
                <i class="fas fa-ellipsis-v"></i>
            </span>
        </button>
    `;
    $('#chatHistory').prepend(chatItem);
}

function toggleSettings() {
    const menu = $('#settingsMenu');
    menu.slideToggle(300);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Add initial chat to history
    addChatToHistory("Welcome Chat");
});
</script>