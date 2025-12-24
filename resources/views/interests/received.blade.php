@extends('layouts.app')

@section('title', 'Received Interests')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-inbox text-primary"></i> Received Interests</h1>
            <p>People who are interested in your profile</p>
        </div>

        <!-- Interest Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('interests.received') }}" class="btn btn-primary">
                <i class="fas fa-inbox"></i> Received
            </a>
            <a href="{{ route('interests.sent') }}" class="btn btn-outline">
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
                            <img src="{{ $interest->sender->getProfilePhotoUrl() }}" alt="{{ $interest->sender->name }}"
                                 style="width: 80px; height: 80px; border-radius: var(--radius-lg); object-fit: cover;">
                            <div class="flex-1">
                                <a href="{{ route('profile.show', $interest->sender->id) }}" style="font-weight: 600; font-size: 1.1rem;">
                                    {{ $interest->sender->name }}
                                </a>
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    {{ $interest->sender->getAge() }} years
                                    @if($interest->sender->profile?->city)
                                        | {{ $interest->sender->profile->city->name }}
                                    @endif
                                </p>
                                <p class="text-muted" style="font-size: 0.8rem;">
                                    {{ $interest->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        
                        @if($interest->isPending())
                            <div class="flex gap-2 p-4" style="border-top: 1px solid var(--gray-100);">
                                <form action="{{ route('interests.accept', $interest->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-block btn-sm">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                </form>
                                <form action="{{ route('interests.reject', $interest->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-block btn-sm">
                                        <i class="fas fa-times"></i> Decline
                                    </button>
                                </form>
                            </div>
                        @elseif($interest->status === 'accepted')
                            <div class="p-4" style="border-top: 1px solid var(--gray-100);">
                                <span class="badge badge-success"><i class="fas fa-check"></i> Accepted</span>
                                <a href="{{ route('chat.conversation', $interest->sender->id) }}" class="btn btn-primary btn-sm" style="float: right;">
                                    <i class="fas fa-comment"></i> Chat
                                </a>
                            </div>
                        @else
                            <div class="p-4" style="border-top: 1px solid var(--gray-100);">
                                <span class="badge badge-danger">Declined</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            @if(method_exists($interests, 'hasPages') && $interests->hasPages())
                <div class="mt-6">
                    {{ $interests->links() }}
                </div>
            @endif
        @else
            <div class="card p-8 text-center">
                <i class="fas fa-inbox fa-4x text-muted mb-4" style="display: block;"></i>
                <h3>No Interests Received Yet</h3>
                <p class="text-muted mb-4">When someone shows interest in your profile, they'll appear here.</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-user-edit"></i> Improve Your Profile
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

