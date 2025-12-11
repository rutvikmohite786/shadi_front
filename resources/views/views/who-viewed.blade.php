@extends('layouts.app')

@section('title', 'Who Viewed My Profile')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-eye text-primary"></i> Who Viewed My Profile</h1>
            <p>See who's interested in your profile</p>
        </div>

        <!-- Views Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('views.who-viewed') }}" class="btn btn-primary">
                <i class="fas fa-eye"></i> Who Viewed Me
            </a>
            <a href="{{ route('views.viewed-by-me') }}" class="btn btn-outline">
                <i class="fas fa-history"></i> Viewed By Me
            </a>
        </div>

        @if(isset($viewers) && count($viewers) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($viewers as $view)
                    <div class="card">
                        <div class="flex gap-4 p-4">
                            <img src="{{ $view->viewer->getProfilePhotoUrl() }}" alt="{{ $view->viewer->name }}"
                                 style="width: 70px; height: 70px; border-radius: var(--radius-lg); object-fit: cover;">
                            <div class="flex-1">
                                <a href="{{ route('profile.show', $view->viewer->id) }}" style="font-weight: 600; font-size: 1.1rem;">
                                    {{ $view->viewer->name }}
                                </a>
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    {{ $view->viewer->getAge() }} years
                                    @if($view->viewer->profile?->city)
                                        | {{ $view->viewer->profile->city->name }}
                                    @endif
                                </p>
                                <p class="text-muted" style="font-size: 0.8rem;">
                                    <i class="far fa-clock"></i> {{ $view->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2 p-4" style="border-top: 1px solid var(--gray-100);">
                            <a href="{{ route('profile.show', $view->viewer->id) }}" class="btn btn-outline btn-sm" style="flex: 1;">
                                View Profile
                            </a>
                            <form action="{{ route('interests.send', $view->viewer->id) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm btn-block">
                                    <i class="fas fa-heart"></i> Interest
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($viewers->hasPages())
                <div class="mt-6">
                    {{ $viewers->links() }}
                </div>
            @endif
        @else
            <div class="card p-8 text-center">
                <i class="fas fa-eye-slash fa-4x text-muted mb-4" style="display: block;"></i>
                <h3>No Profile Views Yet</h3>
                <p class="text-muted mb-4">When someone views your profile, they'll appear here. Complete your profile to attract more views!</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-user-edit"></i> Improve Profile
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

