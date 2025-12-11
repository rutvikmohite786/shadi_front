@php
    $user = $profile;
@endphp

<div class="profile-card card">
    @if($user->is_verified ?? false)
        <span class="verified-badge"><i class="fas fa-check"></i> Verified</span>
    @endif
    
    @if($user->hasActiveSubscription() ?? false)
        <span class="premium-badge"><i class="fas fa-crown"></i> Premium</span>
    @endif
    
    <a href="{{ route('profile.show', $user->id) }}">
        <img src="{{ $user->getProfilePhotoUrl() }}" alt="{{ $user->name }}" class="card-img-top">
    </a>
    
    <div class="card-body">
        <div class="profile-info">
            <a href="{{ route('profile.show', $user->id) }}" class="name">{{ $user->name }}</a>
            <span class="details">
                {{ $user->getAge() ?? 'N/A' }} yrs 
                @if($user->profile?->city)
                    | {{ $user->profile->city->name ?? '' }}
                @endif
            </span>
            @if($user->profile)
                <span class="details">
                    @if($user->profile->education)
                        {{ $user->profile->education->name }}
                    @endif
                    @if($user->profile->occupation)
                        | {{ $user->profile->occupation->name }}
                    @endif
                </span>
            @endif
        </div>
        
        <div class="profile-actions">
            @if(!isset($isShortlisted) || !$isShortlisted)
                <form action="{{ route('matches.shortlist.add', $user->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" title="Add to Shortlist">
                        <i class="far fa-star"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('matches.shortlist.remove', $user->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary btn-sm" title="Remove from Shortlist">
                        <i class="fas fa-star"></i>
                    </button>
                </form>
            @endif
            
            @if(isset($showChat) && $showChat)
                <a href="{{ route('chat.conversation', $user->id) }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-comment"></i>
                </a>
            @endif
            
            <a href="{{ route('profile.show', $user->id) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                View Profile
            </a>
        </div>
    </div>
</div>

