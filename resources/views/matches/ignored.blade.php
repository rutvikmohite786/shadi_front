@extends('layouts.app')

@section('title', 'Ignored Profiles')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-ban text-muted"></i> Ignored Profiles</h1>
            <p>Profiles you've chosen to ignore</p>
        </div>

        <!-- Match Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('matches.daily') }}" class="btn btn-outline">
                <i class="fas fa-calendar-day"></i> Daily Matches
            </a>
            <a href="{{ route('matches.all') }}" class="btn btn-outline">
                <i class="fas fa-users"></i> All Matches
            </a>
            <a href="{{ route('matches.shortlist') }}" class="btn btn-outline">
                <i class="fas fa-star"></i> Shortlisted
            </a>
            <a href="{{ route('matches.ignored') }}" class="btn btn-primary">
                <i class="fas fa-ban"></i> Ignored
            </a>
        </div>

        @if(isset($ignored) && count($ignored) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($ignored as $item)
                    @php $user = $item->ignored ?? $item; @endphp
                    <div class="card">
                        <div class="flex gap-4 p-4">
                            <img src="{{ $user->getProfilePhotoUrl() }}" alt="{{ $user->name }}"
                                 style="width: 70px; height: 70px; border-radius: var(--radius-lg); object-fit: cover; opacity: 0.7;">
                            <div class="flex-1">
                                <span style="font-weight: 600; font-size: 1.1rem; color: var(--gray-500);">
                                    {{ $user->name }}
                                </span>
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    {{ $user->getAge() }} years
                                </p>
                            </div>
                        </div>
                        <div class="p-4" style="border-top: 1px solid var(--gray-100);">
                            <form action="{{ route('matches.unignore', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm btn-block">
                                    <i class="fas fa-undo"></i> Unignore
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($ignored->hasPages())
                <div class="mt-6">
                    {{ $ignored->links() }}
                </div>
            @endif
        @else
            <div class="card p-8 text-center">
                <i class="fas fa-check-circle fa-4x text-success mb-4" style="display: block;"></i>
                <h3>No Ignored Profiles</h3>
                <p class="text-muted">You haven't ignored any profiles yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

