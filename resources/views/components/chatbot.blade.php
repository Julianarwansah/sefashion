{{-- Chatbot Widget --}}
<div id="chatbot-container">
    {{-- Chat Button --}}
    <button id="chatbot-toggle" class="chatbot-toggle" aria-label="Open Chat">
        <svg class="chat-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <svg class="close-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        <span class="notification-badge" id="chatbot-badge" style="display: none;">1</span>
    </button>

    {{-- Chat Window --}}
    <div id="chatbot-window" class="chatbot-window">
        {{-- Header --}}
        <div class="chatbot-header">
            <div class="chatbot-header-content">
                <div class="chatbot-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                <div class="chatbot-info">
                    <h3>SE Fashion Assistant</h3>
                    <p class="chatbot-status">
                        <span class="status-dot"></span>
                        Online
                    </p>
                </div>
            </div>
            <button class="chatbot-minimize" id="chatbot-minimize" aria-label="Minimize">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
        </div>

        {{-- Messages Container --}}
        <div class="chatbot-messages" id="chatbot-messages">
            {{-- Welcome Message --}}
            <div class="message bot-message">
                <div class="message-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <p>Halo! 👋 Saya asisten virtual SE Fashion. Ada yang bisa saya bantu?</p>
                        <p class="message-suggestions">Anda bisa bertanya tentang:</p>
                        <ul class="suggestion-list">
                            <li>📦 Produk dan harga</li>
                            <li>🛍️ Ketersediaan stok</li>
                            <li>📊 Status pesanan</li>
                            <li>💡 Rekomendasi produk</li>
                        </ul>
                    </div>
                    <span class="message-time">Sekarang</span>
                </div>
            </div>
        </div>

        {{-- Typing Indicator --}}
        <div class="typing-indicator" id="typing-indicator" style="display: none;">
            <div class="message bot-message">
                <div class="message-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <div class="typing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="chatbot-input-area">
            <form id="chatbot-form" class="chatbot-form">
                @csrf
                <div class="input-wrapper">
                    <textarea id="chatbot-input" class="chatbot-input" placeholder="Ketik pesan Anda..." rows="1"
                        maxlength="1000"></textarea>
                    <button type="submit" class="chatbot-send" id="chatbot-send" aria-label="Send">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </div>
            </form>
            <div class="chatbot-footer">
                <small>Powered by Google Gemini AI</small>
            </div>
        </div>
    </div>
</div>

{{-- Chatbot Styles --}}
<style>
    /* Chatbot Container */
    #chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    /* Chat Toggle Button */
    .chatbot-toggle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .chatbot-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 30px rgba(102, 126, 234, 0.6);
    }

    .chatbot-toggle svg {
        width: 28px;
        height: 28px;
        color: white;
        transition: all 0.3s ease;
    }

    .chatbot-toggle .close-icon {
        display: none;
        position: absolute;
    }

    .chatbot-toggle.active .chat-icon {
        display: none;
    }

    .chatbot-toggle.active .close-icon {
        display: block;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        border: 2px solid white;
    }

    /* Chat Window */
    .chatbot-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 380px;
        height: 600px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .chatbot-window.active {
        display: flex;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chatbot-header-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chatbot-avatar {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chatbot-avatar svg {
        width: 24px;
        height: 24px;
    }

    .chatbot-info h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .chatbot-status {
        margin: 4px 0 0;
        font-size: 12px;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .chatbot-minimize {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .chatbot-minimize:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .chatbot-minimize svg {
        width: 20px;
        height: 20px;
        color: white;
    }

    /* Messages */
    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f9fafb;
        scroll-behavior: smooth;
    }

    .chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chatbot-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    .chatbot-messages::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .message {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .bot-message .message-avatar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .bot-message .message-avatar svg {
        width: 18px;
        height: 18px;
    }

    .user-message {
        flex-direction: row-reverse;
    }

    .user-message .message-avatar {
        background: #e5e7eb;
        color: #6b7280;
    }

    .message-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .user-message .message-content {
        align-items: flex-end;
    }

    .message-bubble {
        background: white;
        padding: 12px 16px;
        border-radius: 16px;
        max-width: 85%;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .user-message .message-bubble {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .message-bubble p {
        margin: 0 0 8px;
        line-height: 1.5;
        font-size: 14px;
    }

    .message-bubble p:last-child {
        margin-bottom: 0;
    }

    .message-suggestions {
        font-weight: 600;
        margin-top: 8px !important;
    }

    .suggestion-list {
        list-style: none;
        padding: 0;
        margin: 8px 0 0;
    }

    .suggestion-list li {
        padding: 4px 0;
        font-size: 13px;
        opacity: 0.9;
    }

    .message-time {
        font-size: 11px;
        color: #9ca3af;
        padding: 0 4px;
    }

    /* Typing Indicator */
    .typing-indicator {
        padding: 0 20px;
    }

    .typing-dots {
        display: flex;
        gap: 4px;
        padding: 8px 0;
    }

    .typing-dots span {
        width: 8px;
        height: 8px;
        background: #9ca3af;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }

    .typing-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-10px);
        }
    }

    /* Input Area */
    .chatbot-input-area {
        background: white;
        border-top: 1px solid #e5e7eb;
        padding: 16px;
    }

    .chatbot-form {
        margin-bottom: 8px;
    }

    .input-wrapper {
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    .chatbot-input {
        flex: 1;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        resize: none;
        max-height: 120px;
        font-family: inherit;
        transition: border-color 0.2s;
    }

    .chatbot-input:focus {
        outline: none;
        border-color: #667eea;
    }

    .chatbot-send {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .chatbot-send:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .chatbot-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .chatbot-send svg {
        width: 20px;
        height: 20px;
        color: white;
    }

    .chatbot-footer {
        text-align: center;
    }

    .chatbot-footer small {
        color: #9ca3af;
        font-size: 11px;
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
        .chatbot-window {
            width: calc(100vw - 40px);
            height: calc(100vh - 100px);
            bottom: 80px;
            right: 20px;
        }

        #chatbot-container {
            bottom: 10px;
            right: 10px;
        }
    }
</style>

{{-- Chatbot Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatbotToggle = document.getElementById('chatbot-toggle');
        const chatbotWindow = document.getElementById('chatbot-window');
        const chatbotMinimize = document.getElementById('chatbot-minimize');
        const chatbotForm = document.getElementById('chatbot-form');
        const chatbotInput = document.getElementById('chatbot-input');
        const chatbotMessages = document.getElementById('chatbot-messages');
        const typingIndicator = document.getElementById('typing-indicator');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // Toggle chat window
        chatbotToggle.addEventListener('click', function () {
            chatbotToggle.classList.toggle('active');
            chatbotWindow.classList.toggle('active');

            if (chatbotWindow.classList.contains('active')) {
                chatbotInput.focus();
            }
        });

        // Minimize chat
        chatbotMinimize.addEventListener('click', function () {
            chatbotToggle.classList.remove('active');
            chatbotWindow.classList.remove('active');
        });

        // Auto-resize textarea
        chatbotInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Handle form submit
        chatbotForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const message = chatbotInput.value.trim();
            if (!message) return;

            // Add user message
            addMessage(message, 'user');
            chatbotInput.value = '';
            chatbotInput.style.height = 'auto';

            // Show typing indicator
            typingIndicator.style.display = 'block';
            scrollToBottom();

            try {
                // Send to backend
                const response = await fetch('{{ route("chatbot.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                // Hide typing indicator
                typingIndicator.style.display = 'none';

                if (data.success) {
                    addMessage(data.response, 'bot');
                } else {
                    addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
                }
            } catch (error) {
                console.error('Chatbot error:', error);
                typingIndicator.style.display = 'none';
                addMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi.', 'bot');
            }
        });

        // Add message to chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}-message`;

            const time = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            const avatarSvg = sender === 'bot'
                ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/><circle cx="12" cy="12" r="3"/></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>';

            messageDiv.innerHTML = `
            <div class="message-avatar">
                ${avatarSvg}
            </div>
            <div class="message-content">
                <div class="message-bubble">
                    <p>${escapeHtml(text)}</p>
                </div>
                <span class="message-time">${time}</span>
            </div>
        `;

            chatbotMessages.appendChild(messageDiv);
            scrollToBottom();
        }

        // Scroll to bottom
        function scrollToBottom() {
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Allow Enter to send (Shift+Enter for new line)
        chatbotInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatbotForm.dispatchEvent(new Event('submit'));
            }
        });
    });
</script>