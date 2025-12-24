@extends('layouts.app')

@section('title', 'Quick Search Results')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-search text-primary"></i> Search Results</h1>
            <p>Results for "{{ $keyword }}"</p>
        </div>

        <div class="mb-4">
            <a href="{{ route('search.index') }}" class="btn btn-outline">
                <i class="fas fa-sliders-h"></i> Advanced Search
            </a>
        </div>

        @if(isset($results) && count($results) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($results as $profile)
                    @include('components.profile-card', ['profile' => $profile])
                @endforeach
            </div>
            
            @if(method_exists($results, 'hasPages') && $results->hasPages())
                <div class="mt-6">
                    {{ $results->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="card p-8 text-center">
                <i class="fas fa-search fa-4x text-muted mb-4" style="display: block;"></i>
                <h3>No Results Found</h3>
                <p class="text-muted mb-4">No profiles found matching "{{ $keyword }}"</p>
                <a href="{{ route('search.index') }}" class="btn btn-primary">
                    <i class="fas fa-sliders-h"></i> Try Advanced Search
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

