@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name)

@section('content')
<div class="py-8">
    <div class="container">
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <!-- Chat Header -->
            <div class="flex items-center gap-4 p-4" style="border-bottom: 1px solid var(--gray-200);">
                <a href="{{ route('chat.index') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <img src="{{ $otherUser->getProfilePhotoUrl() }}" alt="{{ $otherUser->name }}"
                     style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                <div class="flex-1">
                    <a href="{{ route('profile.show', $otherUser->id) }}" style="font-weight: 600;">{{ $otherUser->name }}</a>
                    <p class="text-muted" style="font-size: 0.8rem;">{{ $otherUser->getAge() }} years</p>
                </div>
                <a href="{{ route('profile.show', $otherUser->id) }}" class="btn btn-outline btn-sm">
                    View Profile
                </a>
            </div>
            
            <!-- Messages Container -->
            <div id="messages-container" style="height: 400px; overflow-y: auto; padding: 1rem; background: var(--gray-50);">
                @if(isset($messages) && count($messages) > 0)
                    @foreach($messages as $message)
                        <div class="mb-3 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                            <div style="display: inline-block; max-width: 70%; padding: 0.75rem 1rem; border-radius: var(--radius-lg); 
                                        {{ $message->sender_id === auth()->id() ? 'background: var(--primary-500); color: white;' : 'background: white;' }}">
                                <p style="margin: 0;">{{ $message->message }}</p>
                            </div>
                            <p class="text-muted mt-1" style="font-size: 0.75rem;">
                                {{ $message->created_at->format('h:i A') }}
                            </p>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comments fa-3x text-muted mb-3" style="display: block;"></i>
                        <p class="text-muted">No messages yet. Start the conversation!</p>
                    </div>
                @endif
            </div>
            
            <!-- Message Input -->
            <form id="chat-form" class="p-4" style="border-top: 1px solid var(--gray-200);">
                @csrf
                <div class="flex gap-2">
                    <input type="text" id="message-input" name="message" class="form-control" placeholder="Type your message..." required autofocus>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Socket.IO Client -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
    var container = document.getElementById('messages-container');
    var chatForm = document.getElementById('chat-form');
    var messageInput = document.getElementById('message-input');
    var currentUserId = {{ auth()->id() }};
    var otherUserId = {{ $otherUser->id }};
    var chatToken = '{{ $chatToken ?? "" }}';
    var socketConnected = false;
    var socket = null;
    
    // Scroll to bottom of messages
    function scrollToBottom() {
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    scrollToBottom();
    
    // Add message to chat UI
    function addMessage(message, isSent, time = null) {
        var emptyMessage = container.querySelector('.text-center.py-8');
        if (emptyMessage) {
            emptyMessage.remove();
        }
        
        var messageDiv = document.createElement('div');
        messageDiv.className = 'mb-3' + (isSent ? ' text-right' : '');
        
        var displayTime = time || new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        
        messageDiv.innerHTML = `
            <div style="display: inline-block; max-width: 70%; padding: 0.75rem 1rem; border-radius: var(--radius-lg); 
                        ${isSent ? 'background: var(--primary-500); color: white;' : 'background: white;'}">
                <p style="margin: 0;">${escapeHtml(message)}</p>
            </div>
            <p class="text-muted mt-1" style="font-size: 0.75rem;">${displayTime}</p>
        `;
        
        container.appendChild(messageDiv);
        scrollToBottom();
    }
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Initialize Socket.IO connection
    function initSocket() {
        if (!chatToken) {
            console.log('No chat token available');
            return;
        }
        
        var socketUrl = '{{ config("chat.socket_url", "http://localhost:3001") }}';
        
        socket = io(socketUrl, {
            auth: { token: chatToken },
            transports: ['websocket', 'polling'],
            reconnection: true,
            reconnectionAttempts: 5,
            reconnectionDelay: 1000
        });
        
        socket.on('connect', function() {
            console.log('Socket connected');
            socketConnected = true;
            
            // Mark messages as read
            socket.emit('mark_read', { senderId: otherUserId });
        });
        
        socket.on('disconnect', function() {
            console.log('Socket disconnected');
            socketConnected = false;
        });
        
        socket.on('connect_error', function(error) {
            console.log('Socket connection error:', error.message);
            socketConnected = false;
        });
        
        // Listen for new messages
        socket.on('new_message', function(data) {
            console.log('New message received:', data);
            if (data.message && data.message.sender_id === otherUserId) {
                var msgTime = new Date(data.message.created_at).toLocaleTimeString('en-US', { 
                    hour: 'numeric', minute: '2-digit', hour12: true 
                });
                addMessage(data.message.message, false, msgTime);
                
                // Mark as read
                socket.emit('mark_read', { senderId: otherUserId });
            }
        });
        
        // Listen for typing indicator
        socket.on('user_typing', function(data) {
            if (data.userId === otherUserId) {
                // Could show typing indicator here
                console.log(data.isTyping ? 'User is typing...' : 'User stopped typing');
            }
        });
        
        // Listen for online status
        socket.on('user_online', function(data) {
            if (data.userId === otherUserId) {
                console.log('User is online');
            }
        });
        
        socket.on('user_offline', function(data) {
            if (data.userId === otherUserId) {
                console.log('User went offline');
            }
        });
    }
    
    // Initialize socket connection
    initSocket();
    
    // Handle form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        var message = messageInput.value.trim();
        if (!message) return;
        
        // Disable input while sending
        messageInput.disabled = true;
        
        // Send via API (this saves to database)
        fetch('{{ route("chat.send", $otherUser->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addMessage(message, true);
                messageInput.value = '';
                
                // Also send via socket for real-time delivery to receiver
                if (socket && socketConnected) {
                    socket.emit('send_message', {
                        receiverId: otherUserId,
                        message: message,
                        messageType: 'text'
                    });
                }
            } else {
                alert(data.message || 'Failed to send message');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message. Please try again.');
        })
        .finally(() => {
            messageInput.disabled = false;
            messageInput.focus();
        });
    });
    
    // Send typing indicator
    var typingTimeout = null;
    messageInput.addEventListener('input', function() {
        if (socket && socketConnected) {
            socket.emit('typing', { receiverId: otherUserId, isTyping: true });
            
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(function() {
                socket.emit('typing', { receiverId: otherUserId, isTyping: false });
            }, 1000);
        }
    });
</script>
@endpush
@endsection

