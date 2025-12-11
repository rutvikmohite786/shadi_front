@extends('layouts.app')

@section('title', 'Premium Plans')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="section-header mb-8">
            <h1><i class="fas fa-crown text-secondary"></i> Premium Plans</h1>
            <p>Upgrade to unlock all features and find your perfect match faster</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" style="max-width: 1000px; margin: 0 auto;">
            @if(isset($plans) && count($plans) > 0)
                @foreach($plans as $plan)
                    <div class="card p-6 text-center {{ $plan->is_popular ? 'border-2' : '' }}" 
                         style="{{ $plan->is_popular ? 'border-color: var(--primary-500); position: relative;' : '' }}">
                        @if($plan->is_popular)
                            <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--primary-500); color: white; padding: 0.25rem 1rem; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 600;">
                                Most Popular
                            </span>
                        @endif
                        
                        <h3 class="mb-2">{{ $plan->name }}</h3>
                        <div class="mb-4">
                            <span style="font-size: 2.5rem; font-weight: 700; color: var(--primary-600);">₹{{ number_format($plan->price) }}</span>
                            <span class="text-muted">/ {{ $plan->duration_days }} days</span>
                        </div>
                        
                        <ul style="list-style: none; text-align: left; margin-bottom: 1.5rem;">
                            <li class="py-2" style="border-bottom: 1px solid var(--gray-100);">
                                <i class="fas fa-check text-success"></i> {{ $plan->contact_views_limit ?: 'Unlimited' }} Contact Views
                            </li>
                            <li class="py-2" style="border-bottom: 1px solid var(--gray-100);">
                                <i class="fas fa-check text-success"></i> {{ $plan->chat_limit ?: 'Unlimited' }} Messages
                            </li>
                            <li class="py-2" style="border-bottom: 1px solid var(--gray-100);">
                                <i class="fas fa-check text-success"></i> View all photos
                            </li>
                            <li class="py-2" style="border-bottom: 1px solid var(--gray-100);">
                                <i class="fas fa-check text-success"></i> Priority in search
                            </li>
                            <li class="py-2">
                                <i class="fas fa-check text-success"></i> Premium badge
                            </li>
                        </ul>
                        
                        <form action="{{ route('subscription.subscribe', $plan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $plan->is_popular ? 'btn-primary' : 'btn-outline' }} btn-block btn-lg">
                                Subscribe Now
                            </button>
                        </form>
                    </div>
                @endforeach
            @else
                <!-- Default plans if none in database -->
                <div class="card p-6 text-center">
                    <h3 class="mb-2">Silver</h3>
                    <div class="mb-4">
                        <span style="font-size: 2.5rem; font-weight: 700; color: var(--primary-600);">₹999</span>
                        <span class="text-muted">/ 30 days</span>
                    </div>
                    <ul style="list-style: none; text-align: left; margin-bottom: 1.5rem;">
                        <li class="py-2"><i class="fas fa-check text-success"></i> 20 Contact Views</li>
                        <li class="py-2"><i class="fas fa-check text-success"></i> 50 Messages</li>
                        <li class="py-2"><i class="fas fa-check text-success"></i> View all photos</li>
                    </ul>
                    <button class="btn btn-outline btn-block btn-lg" disabled>Coming Soon</button>
                </div>
                
                <div class="card p-6 text-center" style="border: 2px solid var(--primary-500); position: relative;">
                    <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--primary-500); color: white; padding: 0.25rem 1rem; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 600;">
                        Most Popular
                    </span>
                    <h3 class="mb-2">Gold</h3>
                    <div class="mb-4">
                        <span style="font-size: 2.5rem; font-weight: 700; color: var(--primary-600);">₹2,499</span>
                        <span class="text-muted">/ 90 days</span>
                    </div>
                    <ul style="list-style: none; text-align: left; margin-bottom: 1.5rem;">
                        <li class="py-2"><i class="fas fa-check text-success"></i> 50 Contact Views</li>
                        <li class="py-2"><i class="fas fa-check text-success"></i> Unlimited Messages</li>
                        <li class="py-2"><i class="fas fa-check text-success"></i> Priority Support</li>
                    </ul>
                    <button class="btn btn-primary btn-block btn-lg" disabled>Coming Soon</button>
                </div>
                
                <div class="card p-6 text-center">
                    <h3 class="mb-2">Platinum</h3>
                    <div class="mb-4">
                        <span style="font-size: 2.5rem; font-weight: 700; color: var(--primary-600);">₹4,999</span>
                        <span class="text-muted">/ 180 days</span>
                    </div>
                    <ul style="list-style: none; text-align: left; margin-bottom: 1.5rem;">
                        <li class="py-2"><i class="fas fa-check text-success"></i> Unlimited Everything</li>
                        <li class="py-2"><i class="fas fa-check text-success"></i> Profile Highlight</li>
                        <li class="py-2"><i class="fas fa-check text-success"></i> Personal Matchmaker</li>
                    </ul>
                    <button class="btn btn-outline btn-block btn-lg" disabled>Coming Soon</button>
                </div>
            @endif
        </div>
        
        <!-- Features Comparison -->
        <div class="mt-8 card p-6" style="max-width: 1000px; margin: 0 auto;">
            <h3 class="mb-4 text-center">Why Go Premium?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h5>View Contact Details</h5>
                    <p class="text-muted">Get phone numbers and email of profiles you like</p>
                </div>
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5>Unlimited Messaging</h5>
                    <p class="text-muted">Chat freely with your connections</p>
                </div>
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5>Stand Out</h5>
                    <p class="text-muted">Get highlighted in search results</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

