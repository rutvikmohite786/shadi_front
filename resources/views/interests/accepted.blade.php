@extends('layouts.app')

@section('title', 'Accepted Interests')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-check-circle text-success"></i> Accepted Interests</h1>
            <p>Mutual connections - Start chatting!</p>
        </div>

        <!-- Interest Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('interests.received') }}" class="btn btn-outline">
                <i class="fas fa-inbox"></i> Received
            </a>
            <a href="{{ route('interests.sent') }}" class="btn btn-outline">
                <i class="fas fa-paper-plane"></i> Sent
            </a>
            <a href="{{ route('interests.accepted') }}" class="btn btn-primary">
                <i class="fas fa-check-circle"></i> Accepted
            </a>
        </div>

        @if(isset($interests) && count($interests) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($interests as $interest)
                    @php
                        $otherUser = $interest->sender_id === auth()->id() ? $interest->receiver : $interest->sender;
                    @endphp
                    <div class="card">
                        <div class="flex gap-4 p-4">
                            <img src="{{ $otherUser->getProfilePhotoUrl() }}" alt="{{ $otherUser->name }}"
                                 style="width: 80px; height: 80px; border-radius: var(--radius-lg); object-fit: cover;">
                            <div class="flex-1">
                                <a href="{{ route('profile.show', $otherUser->id) }}" style="font-weight: 600; font-size: 1.1rem;">
                                    {{ $otherUser->name }}
                                </a>
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    {{ $otherUser->getAge() }} years
                                    @if($otherUser->profile?->city)
                                        | {{ $otherUser->profile->city->name }}
                                    @endif
                                </p>
                                <span class="badge badge-success mt-2">
                                    <i class="fas fa-check"></i> Connected
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 p-4" style="border-top: 1px solid var(--gray-100);">
                            <a href="{{ route('profile.show', $otherUser->id) }}" class="btn btn-outline btn-sm" style="flex: 1;">
                                View Profile
                            </a>
                            <a href="{{ route('chat.conversation', $otherUser->id) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                                <i class="fas fa-comment"></i> Chat
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($interests->hasPages())
                <div class="mt-6">
                    {{ $interests->links() }}
                </div>
            @endif
        @else
            <div class="card p-8 text-center">
                <i class="fas fa-handshake fa-4x text-muted mb-4" style="display: block;"></i>
                <h3>No Accepted Interests Yet</h3>
                <p class="text-muted mb-4">When your interests are accepted or you accept interests, they'll appear here.</p>
                <a href="{{ route('interests.received') }}" class="btn btn-primary">
                    <i class="fas fa-inbox"></i> Check Received Interests
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

