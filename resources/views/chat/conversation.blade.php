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
            <form action="{{ route('chat.send', $otherUser->id) }}" method="POST" class="p-4" style="border-top: 1px solid var(--gray-200);">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="message" class="form-control" placeholder="Type your message..." required autofocus>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Scroll to bottom of messages
    var container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endpush
@endsection

