@extends('layouts.app')

@section('title', 'Mutual Matches')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-heart text-primary"></i> Mutual Matches</h1>
            <p>Profiles where both of you have shown interest</p>
        </div>

        <!-- Match Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('matches.daily') }}" class="btn btn-outline">
                <i class="fas fa-calendar-day"></i> Daily Matches
            </a>
            <a href="{{ route('matches.all') }}" class="btn btn-outline">
                <i class="fas fa-users"></i> All Matches
            </a>
            <a href="{{ route('matches.mutual') }}" class="btn btn-primary">
                <i class="fas fa-heart"></i> Mutual Matches
            </a>
            <a href="{{ route('matches.shortlist') }}" class="btn btn-outline">
                <i class="fas fa-star"></i> Shortlisted
            </a>
        </div>

        @if(isset($matches) && count($matches) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($matches as $match)
                    @include('components.profile-card', ['profile' => $match, 'showChat' => true])
                @endforeach
            </div>
        @else
            <div class="card p-8 text-center">
                <i class="fas fa-handshake fa-4x text-muted mb-4" style="display: block;"></i>
                <h3>No Mutual Matches Yet</h3>
                <p class="text-muted mb-4">When someone you've shown interest in also shows interest in you, they'll appear here.</p>
                <a href="{{ route('matches.daily') }}" class="btn btn-primary">
                    <i class="fas fa-heart"></i> Browse Matches
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

