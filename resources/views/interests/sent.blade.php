@extends('layouts.app')

@section('title', 'Sent Interests')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-paper-plane text-primary"></i> Sent Interests</h1>
            <p>Profiles you've shown interest in</p>
        </div>

        <!-- Interest Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('interests.received') }}" class="btn btn-outline">
                <i class="fas fa-inbox"></i> Received
            </a>
            <a href="{{ route('interests.sent') }}" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Sent
            </a>
            <a href="{{ route('interests.accepted') }}" class="btn btn-outline">
                <i class="fas fa-check-circle"></i> Accepted
            </a>
        </div>

        @if(isset($interests) && count($interests) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($interests as $interest)
                    <div class="card">
                        <div class="flex gap-4 p-4">
                            <img src="{{ $interest->receiver->getProfilePhotoUrl() }}" alt="{{ $interest->receiver->name }}"
                                 style="width: 80px; height: 80px; border-radius: var(--radius-lg); object-fit: cover;">
                            <div class="flex-1">
                                <a href="{{ route('profile.show', $interest->receiver->id) }}" style="font-weight: 600; font-size: 1.1rem;">
                                    {{ $interest->receiver->name }}
                                </a>
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    {{ $interest->receiver->getAge() }} years
                                    @if($interest->receiver->profile?->city)
                                        | {{ $interest->receiver->profile->city->name }}
                                    @endif
                                </p>
                                <p class="text-muted" style="font-size: 0.8rem;">
                                    Sent {{ $interest->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="p-4" style="border-top: 1px solid var(--gray-100);">
                            @if($interest->status === 'pending')
                                <span class="badge badge-secondary"><i class="fas fa-clock"></i> Pending</span>
                                <form action="{{ route('interests.cancel', $interest->id) }}" method="POST" style="display: inline; float: right;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Cancel this interest?')">
                                        Cancel
                                    </button>
                                </form>
                            @elseif($interest->status === 'accepted')
                                <span class="badge badge-success"><i class="fas fa-check"></i> Accepted</span>
                                <a href="{{ route('chat.conversation', $interest->receiver->id) }}" class="btn btn-primary btn-sm" style="float: right;">
                                    <i class="fas fa-comment"></i> Chat
                                </a>
                            @else
                                <span class="badge badge-danger">Declined</span>
                            @endif
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
                <i class="fas fa-paper-plane fa-4x text-muted mb-4" style="display: block;"></i>
                <h3>No Interests Sent Yet</h3>
                <p class="text-muted mb-4">Start connecting by sending interests to profiles you like.</p>
                <a href="{{ route('matches.daily') }}" class="btn btn-primary">
                    <i class="fas fa-heart"></i> Browse Matches
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

