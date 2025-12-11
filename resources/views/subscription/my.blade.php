@extends('layouts.app')

@section('title', 'My Subscription')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-crown text-secondary"></i> My Subscription</h1>
            <p>Manage your premium subscription</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" style="max-width: 900px;">
            @if(isset($subscription) && $subscription)
                <!-- Active Subscription -->
                <div class="card p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--secondary-400), var(--secondary-600)); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-crown fa-2x" style="color: white;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $subscription->plan->name }}</h3>
                            <span class="badge badge-success">Active</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex justify-between py-2" style="border-bottom: 1px solid var(--gray-100);">
                            <span class="text-muted">Plan</span>
                            <span style="font-weight: 500;">{{ $subscription->plan->name }}</span>
                        </div>
                        <div class="flex justify-between py-2" style="border-bottom: 1px solid var(--gray-100);">
                            <span class="text-muted">Start Date</span>
                            <span style="font-weight: 500;">{{ $subscription->start_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2" style="border-bottom: 1px solid var(--gray-100);">
                            <span class="text-muted">End Date</span>
                            <span style="font-weight: 500;">{{ $subscription->end_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-muted">Days Remaining</span>
                            <span style="font-weight: 500; color: var(--primary-600);">{{ $subscription->end_date->diffInDays(now()) }} days</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('subscription.cancel') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-block" onclick="return confirm('Are you sure you want to cancel your subscription?')">
                            Cancel Subscription
                        </button>
                    </form>
                </div>
                
                <!-- Usage Stats -->
                <div class="card p-6">
                    <h4 class="mb-4">Usage This Period</h4>
                    
                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <span>Contact Views</span>
                            <span>{{ $subscription->contacts_viewed ?? 0 }} / {{ $subscription->plan->contact_views_limit ?: '∞' }}</span>
                        </div>
                        <div style="height: 8px; background: var(--gray-200); border-radius: 4px; overflow: hidden;">
                            @php
                                $contactPercent = $subscription->plan->contact_views_limit 
                                    ? min(100, ($subscription->contacts_viewed / $subscription->plan->contact_views_limit) * 100) 
                                    : 0;
                            @endphp
                            <div style="width: {{ $contactPercent }}%; height: 100%; background: var(--primary-500);"></div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <span>Messages Sent</span>
                            <span>{{ $subscription->messages_sent ?? 0 }} / {{ $subscription->plan->chat_limit ?: '∞' }}</span>
                        </div>
                        <div style="height: 8px; background: var(--gray-200); border-radius: 4px; overflow: hidden;">
                            @php
                                $messagePercent = $subscription->plan->chat_limit 
                                    ? min(100, ($subscription->messages_sent / $subscription->plan->chat_limit) * 100) 
                                    : 0;
                            @endphp
                            <div style="width: {{ $messagePercent }}%; height: 100%; background: var(--secondary-500);"></div>
                        </div>
                    </div>
                    
                    <a href="{{ route('subscription.plans') }}" class="btn btn-primary btn-block mt-4">
                        Upgrade Plan
                    </a>
                </div>
            @else
                <!-- No Active Subscription -->
                <div class="lg:col-span-2">
                    <div class="card p-8 text-center">
                        <i class="fas fa-crown fa-4x text-muted mb-4" style="display: block;"></i>
                        <h3>No Active Subscription</h3>
                        <p class="text-muted mb-4">Subscribe to a premium plan to unlock all features and connect with more profiles.</p>
                        <a href="{{ route('subscription.plans') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-crown"></i> View Plans
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

