@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-comments text-primary"></i> Messages</h1>
            <p>Chat with your connections</p>
        </div>

        <div class="card">
            @if(isset($conversations) && count($conversations) > 0)
                <div class="divide-y">
                    @foreach($conversations as $conversation)
                        <a href="{{ route('chat.conversation', $conversation->user->id) }}" 
                           class="flex items-center gap-4 p-4 hover:bg-gray-50" 
                           style="text-decoration: none; color: inherit; display: flex; border-bottom: 1px solid var(--gray-100);">
                            <div style="position: relative;">
                                <img src="{{ $conversation->user->getProfilePhotoUrl() }}" alt="{{ $conversation->user->name }}"
                                     style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover;">
                                @if($conversation->unread_count > 0)
                                    <span style="position: absolute; top: -4px; right: -4px; background: var(--primary-500); color: white; width: 20px; height: 20px; border-radius: 50%; font-size: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                        {{ $conversation->unread_count }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <span style="font-weight: 600;">{{ $conversation->user->name }}</span>
                                    <span class="text-muted" style="font-size: 0.8rem;">{{ $conversation->last_message_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-muted truncate" style="font-size: 0.9rem;">
                                    {{ Str::limit($conversation->last_message, 50) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <i class="fas fa-comments fa-4x text-muted mb-4" style="display: block;"></i>
                    <h3>No Messages Yet</h3>
                    <p class="text-muted mb-4">Start chatting with your accepted connections!</p>
                    <a href="{{ route('interests.accepted') }}" class="btn btn-primary">
                        <i class="fas fa-users"></i> View Connections
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

