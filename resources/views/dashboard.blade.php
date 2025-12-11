@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<div style="background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-800) 100%); color: white; padding: 2rem 0;">
    <div class="container">
        <div class="flex items-center gap-4">
            <img src="{{ $user->getProfilePhotoUrl() }}" alt="{{ $user->name }}" 
                 style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid white;">
            <div>
                <h1 style="color: white; margin-bottom: 0.25rem;">Welcome back, {{ $user->name }}! 👋</h1>
                <p style="opacity: 0.9; margin: 0;">Find your perfect match today</p>
            </div>
        </div>
    </div>
</div>

<div class="py-8">
    <div class="container">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card">
                <div class="stat-card-icon primary">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-card-content">
                    <h3>{{ $stats['profile_views'] ?? 0 }}</h3>
                    <p>Profile Views</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-icon secondary">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-card-content">
                    <h3>{{ $stats['received_interests'] ?? 0 }}</h3>
                    <p>Interests Received</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-content">
                    <h3>{{ $stats['accepted_interests'] ?? 0 }}</h3>
                    <p>Accepted</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-icon info">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-card-content">
                    <h3>{{ $stats['shortlisted'] ?? 0 }}</h3>
                    <p>Shortlisted</p>
                </div>
            </div>
        </div>

        <!-- Nearby Users Section -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-map-marker-alt text-primary"></i> Profiles Near You</h2>
                    <p class="text-muted">Matches based on your location and preferences</p>
                </div>
                <a href="{{ route('search.index') }}" class="btn btn-outline">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            @if(isset($nearbyUsers) && count($nearbyUsers) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($nearbyUsers as $nearbyUser)
                        <div class="card profile-card">
                            @if($nearbyUser->is_verified)
                                <span class="verified-badge"><i class="fas fa-check"></i></span>
                            @endif
                            <a href="{{ route('profile.show', $nearbyUser->id) }}">
                                <img src="{{ $nearbyUser->getProfilePhotoUrl() }}" alt="{{ $nearbyUser->name }}" class="card-img-top" style="height: 200px;">
                            </a>
                            <div class="card-body" style="padding: 1rem;">
                                <a href="{{ route('profile.show', $nearbyUser->id) }}" class="name" style="font-weight: 600; display: block;">
                                    {{ $nearbyUser->name }}
                                </a>
                                <p class="text-muted" style="font-size: 0.85rem; margin: 0.25rem 0;">
                                    {{ $nearbyUser->getAge() ?? 'N/A' }} yrs
                                    @if($nearbyUser->profile?->city)
                                        • {{ $nearbyUser->profile->city->name }}
                                    @endif
                                </p>
                                @if($nearbyUser->profile?->education)
                                    <p class="text-muted" style="font-size: 0.8rem; margin: 0;">
                                        {{ $nearbyUser->profile->education->name }}
                                    </p>
                                @endif
                                <div class="flex gap-2 mt-3">
                                    <form action="{{ route('interests.send', $nearbyUser->id) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('matches.shortlist.add', $nearbyUser->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">
                                            <i class="far fa-star"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card p-6 text-center">
                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3" style="display: block;"></i>
                    <p class="text-muted">Complete your profile with location details to see nearby matches</p>
                    <a href="{{ route('profile.edit') }}#location" class="btn btn-primary mt-3">Add Location</a>
                </div>
            @endif
        </section>

        <!-- Premium Profiles Section -->
        @if(isset($premiumProfiles) && count($premiumProfiles) > 0)
        <section class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-crown text-secondary"></i> Premium Profiles</h2>
                    <p class="text-muted">Featured verified members</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($premiumProfiles as $premium)
                    <div class="card profile-card" style="border: 2px solid var(--secondary-400);">
                        <span class="premium-badge"><i class="fas fa-crown"></i> Premium</span>
                        <a href="{{ route('profile.show', $premium->id) }}">
                            <img src="{{ $premium->getProfilePhotoUrl() }}" alt="{{ $premium->name }}" class="card-img-top" style="height: 200px;">
                        </a>
                        <div class="card-body" style="padding: 1rem;">
                            <a href="{{ route('profile.show', $premium->id) }}" class="name" style="font-weight: 600; display: block;">
                                {{ $premium->name }}
                            </a>
                            <p class="text-muted" style="font-size: 0.85rem; margin: 0.25rem 0;">
                                {{ $premium->getAge() ?? 'N/A' }} yrs
                                @if($premium->profile?->city)
                                    • {{ $premium->profile->city->name }}
                                @endif
                            </p>
                            <a href="{{ route('profile.show', $premium->id) }}" class="btn btn-secondary btn-sm btn-block mt-3">
                                View Profile
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Daily Matches Section -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-heart text-primary"></i> Today's Matches</h2>
                    <p class="text-muted">Handpicked profiles just for you</p>
                </div>
                <a href="{{ route('matches.daily') }}" class="btn btn-outline">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            @if(count($dailyMatches) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($dailyMatches as $match)
                        <div class="card">
                            <a href="{{ route('profile.show', $match->id) }}">
                                <img src="{{ $match->getProfilePhotoUrl() }}" alt="{{ $match->name }}" 
                                     style="width: 100%; height: 150px; object-fit: cover;">
                            </a>
                            <div class="p-3 text-center">
                                <a href="{{ route('profile.show', $match->id) }}" style="font-weight: 600; font-size: 0.9rem;">
                                    {{ Str::limit($match->name, 12) }}
                                </a>
                                <p class="text-muted" style="font-size: 0.8rem; margin: 0;">{{ $match->getAge() }} yrs</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card p-6 text-center">
                    <p class="text-muted">Complete your profile to get personalized matches</p>
                </div>
            @endif
        </section>

        <!-- New Profiles Section -->
        @if(count($newProfiles) > 0)
        <section class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-user-plus text-success"></i> New Profiles</h2>
                    <p class="text-muted">Recently joined members</p>
                </div>
                <a href="{{ route('search.index') }}" class="btn btn-outline">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($newProfiles as $profile)
                    <div class="card">
                        <a href="{{ route('profile.show', $profile->id) }}">
                            <img src="{{ $profile->getProfilePhotoUrl() }}" alt="{{ $profile->name }}" 
                                 style="width: 100%; height: 150px; object-fit: cover;">
                        </a>
                        <div class="p-3 text-center">
                            <a href="{{ route('profile.show', $profile->id) }}" style="font-weight: 600; font-size: 0.9rem;">
                                {{ Str::limit($profile->name, 12) }}
                            </a>
                            <p class="text-muted" style="font-size: 0.8rem; margin: 0;">{{ $profile->getAge() }} yrs</p>
                            <span class="badge badge-success" style="font-size: 0.7rem;">New</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Quick Actions -->
        <section class="mb-8">
            <h2 class="mb-4"><i class="fas fa-bolt text-secondary"></i> Quick Actions</h2>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                <a href="{{ route('profile.edit') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <span>Edit Profile</span>
                </a>
                
                <a href="{{ route('search.index') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <span>Search</span>
                </a>
                
                <a href="{{ route('matches.daily') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <span>Matches</span>
                </a>
                
                <a href="{{ route('interests.received') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <span>Interests</span>
                </a>
                
                <a href="{{ route('chat.index') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span>Messages</span>
                </a>
                
                <a href="{{ route('views.who-viewed') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <span>Who Viewed</span>
                </a>
            </div>
        </section>

        <!-- Subscription Plans Section -->
        @if(!$user->hasActiveSubscription())
        <section class="mb-8" style="background: linear-gradient(135deg, var(--primary-50) 0%, var(--secondary-50) 100%); margin: 0 -1rem; padding: 3rem 1rem; border-radius: var(--radius-xl);">
            <div class="text-center mb-6">
                <h2><i class="fas fa-crown text-secondary"></i> Upgrade to Premium</h2>
                <p class="text-muted">Unlock all features and find your match faster</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" style="max-width: 900px; margin: 0 auto;">
                @if(isset($plans) && count($plans) > 0)
                    @foreach($plans as $index => $plan)
                        <div class="card p-4 text-center {{ $index == 1 ? 'border-2' : '' }}" 
                             style="{{ $index == 1 ? 'border-color: var(--primary-500); transform: scale(1.05);' : '' }}">
                            @if($index == 1)
                                <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--primary-500); color: white; padding: 0.25rem 1rem; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 600;">
                                    Popular
                                </span>
                            @endif
                            <h4 class="mb-2">{{ $plan->name }}</h4>
                            <div class="mb-3">
                                <span style="font-size: 2rem; font-weight: 700; color: var(--primary-600);">₹{{ number_format($plan->price) }}</span>
                                <span class="text-muted">/ {{ $plan->duration_days }} days</span>
                            </div>
                            <ul style="list-style: none; text-align: left; font-size: 0.9rem; margin-bottom: 1rem;">
                                <li class="py-1"><i class="fas fa-check text-success"></i> Contact {{ $plan->contact_views_limit ?: 'Unlimited' }} profiles</li>
                                <li class="py-1"><i class="fas fa-check text-success"></i> {{ $plan->chat_limit ?: 'Unlimited' }} messages</li>
                                <li class="py-1"><i class="fas fa-check text-success"></i> View all photos</li>
                            </ul>
                            <a href="{{ route('subscription.plans') }}" class="btn {{ $index == 1 ? 'btn-primary' : 'btn-outline' }} btn-block">
                                Choose Plan
                            </a>
                        </div>
                    @endforeach
                @else
                    <!-- Default plans when no plans in database -->
                    <div class="card p-4 text-center">
                        <h4 class="mb-2">Silver</h4>
                        <div class="mb-3">
                            <span style="font-size: 2rem; font-weight: 700; color: var(--primary-600);">₹999</span>
                            <span class="text-muted">/ 30 days</span>
                        </div>
                        <ul style="list-style: none; text-align: left; font-size: 0.9rem; margin-bottom: 1rem;">
                            <li class="py-1"><i class="fas fa-check text-success"></i> Contact 20 profiles</li>
                            <li class="py-1"><i class="fas fa-check text-success"></i> 50 messages</li>
                            <li class="py-1"><i class="fas fa-check text-success"></i> View all photos</li>
                        </ul>
                        <a href="{{ route('subscription.plans') }}" class="btn btn-outline btn-block">Choose Plan</a>
                    </div>
                    
                    <div class="card p-4 text-center" style="border: 2px solid var(--primary-500); transform: scale(1.05); position: relative;">
                        <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--primary-500); color: white; padding: 0.25rem 1rem; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 600;">
                            Popular
                        </span>
                        <h4 class="mb-2">Gold</h4>
                        <div class="mb-3">
                            <span style="font-size: 2rem; font-weight: 700; color: var(--primary-600);">₹2,499</span>
                            <span class="text-muted">/ 90 days</span>
                        </div>
                        <ul style="list-style: none; text-align: left; font-size: 0.9rem; margin-bottom: 1rem;">
                            <li class="py-1"><i class="fas fa-check text-success"></i> Contact 50 profiles</li>
                            <li class="py-1"><i class="fas fa-check text-success"></i> Unlimited messages</li>
                            <li class="py-1"><i class="fas fa-check text-success"></i> Priority listing</li>
                        </ul>
                        <a href="{{ route('subscription.plans') }}" class="btn btn-primary btn-block">Choose Plan</a>
                    </div>
                    
                    <div class="card p-4 text-center">
                        <h4 class="mb-2">Platinum</h4>
                        <div class="mb-3">
                            <span style="font-size: 2rem; font-weight: 700; color: var(--primary-600);">₹4,999</span>
                            <span class="text-muted">/ 180 days</span>
                        </div>
                        <ul style="list-style: none; text-align: left; font-size: 0.9rem; margin-bottom: 1rem;">
                            <li class="py-1"><i class="fas fa-check text-success"></i> Unlimited contacts</li>
                            <li class="py-1"><i class="fas fa-check text-success"></i> Unlimited messages</li>
                            <li class="py-1"><i class="fas fa-check text-success"></i> Profile highlight</li>
                        </ul>
                        <a href="{{ route('subscription.plans') }}" class="btn btn-outline btn-block">Choose Plan</a>
                    </div>
                @endif
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('subscription.plans') }}" class="text-primary" style="font-weight: 500;">
                    View All Plans & Features <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </section>
        @endif

        <!-- Recent Activity -->
        <section>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Who Viewed You -->
                <div class="card p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="m-0"><i class="fas fa-eye text-primary"></i> Who Viewed You</h4>
                        <a href="{{ route('views.who-viewed') }}" class="text-primary" style="font-size: 0.9rem;">View All</a>
                    </div>
                    
                    @if(count($recentProfileViews) > 0)
                        @foreach($recentProfileViews as $view)
                            <div class="flex items-center gap-3 py-3" style="border-bottom: 1px solid var(--gray-100);">
                                <img src="{{ $view->viewer->getProfilePhotoUrl() }}" alt="{{ $view->viewer->name }}" 
                                     style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                                <div class="flex-1">
                                    <a href="{{ route('profile.show', $view->viewer->id) }}" style="font-weight: 500;">
                                        {{ $view->viewer->name }}
                                    </a>
                                    <p class="text-muted" style="font-size: 0.8rem; margin: 0;">
                                        {{ $view->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <form action="{{ route('interests.send', $view->viewer->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No profile views yet</p>
                    @endif
                </div>

                <!-- Recent Interests -->
                <div class="card p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="m-0"><i class="fas fa-heart text-primary"></i> Recent Interests</h4>
                        <a href="{{ route('interests.received') }}" class="text-primary" style="font-size: 0.9rem;">View All</a>
                    </div>
                    
                    @if(count($receivedInterests) > 0)
                        @foreach($receivedInterests as $interest)
                            <div class="flex items-center gap-3 py-3" style="border-bottom: 1px solid var(--gray-100);">
                                <img src="{{ $interest->sender->getProfilePhotoUrl() }}" alt="{{ $interest->sender->name }}" 
                                     style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                                <div class="flex-1">
                                    <a href="{{ route('profile.show', $interest->sender->id) }}" style="font-weight: 500;">
                                        {{ $interest->sender->name }}
                                    </a>
                                    <p class="text-muted" style="font-size: 0.8rem; margin: 0;">
                                        {{ $interest->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if($interest->status === 'pending')
                                    <form action="{{ route('interests.accept', $interest->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Accept</button>
                                    </form>
                                @else
                                    <span class="badge badge-{{ $interest->status === 'accepted' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($interest->status) }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No interests received yet</p>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
