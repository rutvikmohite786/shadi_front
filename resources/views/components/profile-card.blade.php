@php
    // Support both $user and $profile variable names
    $user = $user ?? $profile ?? null;
@endphp

@if($user)

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
            @php
                $isShortlistedValue = isset($isShortlisted) ? $isShortlisted : false;
                if (auth()->check() && !isset($isShortlisted)) {
                    try {
                        $matchService = app(\App\Services\MatchService::class);
                        $isShortlistedValue = $matchService->isShortlisted(auth()->id(), $user->id);
                    } catch (\Exception $e) {
                        $isShortlistedValue = false;
                    }
                }
            @endphp
            <button 
                type="button" 
                class="btn btn-sm shortlist-btn {{ $isShortlistedValue ? 'btn-secondary' : 'btn-outline' }}" 
                data-user-id="{{ $user->id }}"
                data-shortlisted="{{ $isShortlistedValue ? '1' : '0' }}"
                title="{{ $isShortlistedValue ? 'Remove from Shortlist' : 'Add to Shortlist' }}"
                onclick="handleShortlistToggle({{ $user->id }}, this)">
                <i class="{{ $isShortlistedValue ? 'fas' : 'far' }} fa-star"></i>
            </button>
            
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
    
    @if(isset($matchScore))
        <div class="text-center py-2" style="background: var(--gray-50); border-top: 1px solid var(--gray-100);">
            <span class="text-muted" style="font-size: 0.8rem;">
                <i class="fas fa-percentage"></i> {{ number_format($matchScore, 0) }}% Match
            </span>
        </div>
    @endif
</div>
@endif

