@extends('layouts.app')

@section('title', 'Shortlisted Profiles')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-star text-secondary"></i> Shortlisted Profiles</h1>
            <p>Profiles you've saved for later</p>
        </div>

        <!-- Match Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('matches.daily') }}" class="btn btn-outline">
                <i class="fas fa-calendar-day"></i> Daily Matches
            </a>
            <a href="{{ route('matches.all') }}" class="btn btn-outline">
                <i class="fas fa-users"></i> All Matches
            </a>
            <a href="{{ route('matches.mutual') }}" class="btn btn-outline">
                <i class="fas fa-heart"></i> Mutual Matches
            </a>
            <a href="{{ route('matches.shortlist') }}" class="btn btn-primary">
                <i class="fas fa-star"></i> Shortlisted
            </a>
        </div>

        @if(isset($shortlisted) && count($shortlisted) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($shortlisted as $item)
                    @include('components.profile-card', ['profile' => $item->user ?? $item, 'isShortlisted' => true])
                @endforeach
            </div>
        @else
            <div class="card p-8 text-center">
                <i class="fas fa-star fa-4x text-muted mb-4" style="display: block;"></i>
                <h3>No Shortlisted Profiles</h3>
                <p class="text-muted mb-4">Click the star icon on profiles you like to add them to your shortlist.</p>
                <a href="{{ route('matches.daily') }}" class="btn btn-primary">
                    <i class="fas fa-heart"></i> Browse Matches
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

