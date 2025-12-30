@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name)

@section('content')
<div class="py-8">
    <div class="container">
        <div class="card" style="max-width: 900px; margin: 0 auto; padding: 0; overflow: hidden;">
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
            <div id="messages-container" style="height: calc(100vh - 280px); min-height: 500px; max-height: 700px; overflow-y: auto; padding: 1rem; background: #e5ddd5; background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iY2hhdCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiPjxwYXRoIGQ9Ik0wIDBoMTAwdjEwMEgweiIgZmlsbD0iI2U1ZGRkNSIvPjxwYXRoIGQ9Ik0yMCAyMGg2MHY2MEgyMHoiIGZpbGw9IiNmZmYiIG9wYWNpdHk9IjAuMDMiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJ1cmwoI2NoYXQpIi8+PC9zdmc+');">
                @if(isset($messages) && count($messages) > 0)
                    @foreach($messages as $message)
                        @php
                            $isSent = $message->sender_id === auth()->id();
                        @endphp
                        <div style="display: flex; margin-bottom: 0.5rem; justify-content: {{ $isSent ? 'flex-end' : 'flex-start' }}; align-items: flex-end;">
                            @if(!$isSent)
                                <img src="{{ $otherUser->getProfilePhotoUrl() }}" alt="{{ $otherUser->name }}"
                                     style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px; flex-shrink: 0;">
                            @endif
                            <div style="max-width: 65%; display: flex; flex-direction: column; align-items: {{ $isSent ? 'flex-end' : 'flex-start' }};">
                                <div style="padding: 8px 12px; border-radius: 7.5px; word-wrap: break-word; 
                                            {{ $isSent ? 'background: #dcf8c6; border-bottom-right-radius: 2px;' : 'background: #ffffff; border-bottom-left-radius: 2px; box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);' }}">
                                    <p style="margin: 0; font-size: 14.2px; line-height: 19px; color: {{ $isSent ? '#303030' : '#111b21' }};">{!! nl2br(e($message->message)) !!}</p>
                                </div>
                                <span style="font-size: 11px; color: #667781; margin-top: 2px; padding: 0 4px;">
                                    {{ $message->created_at->format('h:i A') }}
                                </span>
                            </div>
                            @if($isSent)
                                <img src="{{ auth()->user()->getProfilePhotoUrl() }}" alt="{{ auth()->user()->name }}"
                                     style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-left: 8px; flex-shrink: 0;">
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comments fa-3x text-muted mb-3" style="display: block; color: #667781;"></i>
                        <p class="text-muted" style="color: #667781;">No messages yet. Start the conversation!</p>
                    </div>
                @endif
            </div>
            
            <!-- Message Input -->
            <form id="chat-form" class="p-3" style="border-top: 1px solid var(--gray-200); background: #f0f2f5;">
                @csrf
                <div class="flex gap-2" style="align-items: center;">
                    <div style="flex: 1; display: flex; align-items: center; background: white; border-radius: 21px; padding: 8px 16px; border: 1px solid #e4e6eb;">
                        <input type="text" id="message-input" name="message" 
                               placeholder="Type a message" 
                               style="border: none; outline: none; flex: 1; font-size: 15px; background: transparent; padding: 0;"
                               required autofocus>
                    </div>
                    <button type="submit" style="background: var(--primary-500); color: white; border: none; border-radius: 50%; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s;" 
                            onmouseover="this.style.background='var(--primary-600)'" 
                            onmouseout="this.style.background='var(--primary-500)'">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    #messages-container {
        scrollbar-width: thin;
        scrollbar-color: rgba(0,0,0,0.2) transparent;
    }
    
    #messages-container::-webkit-scrollbar {
        width: 6px;
    }
    
    #messages-container::-webkit-scrollbar-track {
        background: transparent;
    }
    
    #messages-container::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.2);
        border-radius: 3px;
    }
    
    #messages-container::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0,0,0,0.3);
    }
    
    @media (max-width: 768px) {
        #messages-container {
            height: calc(100vh - 240px) !important;
            min-height: 400px !important;
        }
    }
</style>
@endpush

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
        
        var displayTime = time || new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        
        var messageDiv = document.createElement('div');
        messageDiv.style.cssText = 'display: flex; margin-bottom: 0.5rem; justify-content: ' + (isSent ? 'flex-end' : 'flex-start') + '; align-items: flex-end;';
        
        var currentUserPhoto = '{{ auth()->user()->getProfilePhotoUrl() }}';
        var otherUserPhoto = '{{ $otherUser->getProfilePhotoUrl() }}';
        var currentUserName = '{{ auth()->user()->name }}';
        var otherUserName = '{{ $otherUser->name }}';
        
        var photoHtml = '';
        var messageContentHtml = '';
        
        if (isSent) {
            messageContentHtml = `
                <div style="max-width: 65%; display: flex; flex-direction: column; align-items: flex-end;">
                    <div style="padding: 8px 12px; border-radius: 7.5px; word-wrap: break-word; background: #dcf8c6; border-bottom-right-radius: 2px;">
                        <p style="margin: 0; font-size: 14.2px; line-height: 19px; color: #303030;">${formatMessage(message)}</p>
                    </div>
                    <span style="font-size: 11px; color: #667781; margin-top: 2px; padding: 0 4px;">${displayTime}</span>
                </div>
            `;
            photoHtml = `<img src="${currentUserPhoto}" alt="${currentUserName}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-left: 8px; flex-shrink: 0;">`;
        } else {
            photoHtml = `<img src="${otherUserPhoto}" alt="${otherUserName}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px; flex-shrink: 0;">`;
            messageContentHtml = `
                <div style="max-width: 65%; display: flex; flex-direction: column; align-items: flex-start;">
                    <div style="padding: 8px 12px; border-radius: 7.5px; word-wrap: break-word; background: #ffffff; border-bottom-left-radius: 2px; box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);">
                        <p style="margin: 0; font-size: 14.2px; line-height: 19px; color: #111b21;">${formatMessage(message)}</p>
                    </div>
                    <span style="font-size: 11px; color: #667781; margin-top: 2px; padding: 0 4px;">${displayTime}</span>
                </div>
            `;
        }
        
        messageDiv.innerHTML = photoHtml + messageContentHtml;
        container.appendChild(messageDiv);
        scrollToBottom();
    }
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = String(text);
        return div.innerHTML;
    }
    
    // Convert newlines to <br> tags
    function formatMessage(text) {
        if (!text) return '';
        return escapeHtml(String(text)).replace(/\n/g, '<br>');
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
                // Use the message from input (already plain text) or from response if available
                var messageToDisplay = data.data && data.data.message ? data.data.message : message;
                addMessage(messageToDisplay, true);
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

