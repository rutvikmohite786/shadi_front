@extends('layouts.app')

@section('title', 'Profiles Viewed By Me')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-history text-primary"></i> Profiles Viewed By Me</h1>
            <p>Your recently viewed profiles</p>
        </div>

        <!-- Views Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('views.who-viewed') }}" class="btn btn-outline">
                <i class="fas fa-eye"></i> Who Viewed Me
            </a>
            <a href="{{ route('views.viewed-by-me') }}" class="btn btn-primary">
                <i class="fas fa-history"></i> Viewed By Me
            </a>
        </div>

        @if(isset($viewed) && count($viewed) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($viewed as $view)
                    <div class="card">
                        <div class="flex gap-4 p-4">
                            <img src="{{ $view->viewed->getProfilePhotoUrl() }}" alt="{{ $view->viewed->name }}"
                                 style="width: 70px; height: 70px; border-radius: var(--radius-lg); object-fit: cover;">
                            <div class="flex-1">
                                <a href="{{ route('profile.show', $view->viewed->id) }}" style="font-weight: 600; font-size: 1.1rem;">
                                    {{ $view->viewed->name }}
                                </a>
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    {{ $view->viewed->getAge() }} years
                                    @if($view->viewed->profile?->city)
                                        | {{ $view->viewed->profile->city->name }}
                                    @endif
                                </p>
                                <p class="text-muted" style="font-size: 0.8rem;">
                                    <i class="far fa-clock"></i> Viewed {{ $view->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2 p-4" style="border-top: 1px solid var(--gray-100);">
                            <a href="{{ route('profile.show', $view->viewed->id) }}" class="btn btn-outline btn-sm" style="flex: 1;">
                                View Again
                            </a>
                            <form action="{{ route('interests.send', $view->viewed->id) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm btn-block">
                                    <i class="fas fa-heart"></i> Interest
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($viewed->hasPages())
                <div class="mt-6">
                    {{ $viewed->links() }}
                </div>
            @endif
        @else
            <div class="card p-8 text-center">
                <i class="fas fa-history fa-4x text-muted mb-4" style="display: block;"></i>
                <h3>No Profiles Viewed Yet</h3>
                <p class="text-muted mb-4">Start browsing profiles to build your viewing history.</p>
                <a href="{{ route('matches.daily') }}" class="btn btn-primary">
                    <i class="fas fa-heart"></i> Browse Matches
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

