@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <h1><i class="fas fa-search text-primary"></i> Search Results</h1>
            <p>Found {{ $results->total() ?? 0 }} profiles matching your criteria</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Filters Sidebar -->
            <div>
                <div class="card p-4">
                    <h4 class="mb-4">Refine Search</h4>
                    <form action="{{ route('search.results') }}" method="GET">
                        <div class="form-group">
                            <label class="form-label">Age Range</label>
                            <div class="flex gap-2">
                                <input type="number" name="age_min" class="form-control" placeholder="Min" 
                                       value="{{ $filters['age_min'] ?? 18 }}" min="18" max="70">
                                <input type="number" name="age_max" class="form-control" placeholder="Max" 
                                       value="{{ $filters['age_max'] ?? 50 }}" min="18" max="70">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Religion</label>
                            <select name="religion_id" class="form-select">
                                <option value="">Any Religion</option>
                                @foreach($religions ?? [] as $religion)
                                    <option value="{{ $religion->id }}" {{ ($filters['religion_id'] ?? '') == $religion->id ? 'selected' : '' }}>
                                        {{ $religion->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mother Tongue</label>
                            <select name="mother_tongue_id" class="form-select">
                                <option value="">Any Language</option>
                                @foreach($motherTongues ?? [] as $tongue)
                                    <option value="{{ $tongue->id }}" {{ ($filters['mother_tongue_id'] ?? '') == $tongue->id ? 'selected' : '' }}>
                                        {{ $tongue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Marital Status</label>
                            <select name="marital_status" class="form-select">
                                <option value="">Any</option>
                                <option value="never_married" {{ ($filters['marital_status'] ?? '') == 'never_married' ? 'selected' : '' }}>Never Married</option>
                                <option value="divorced" {{ ($filters['marital_status'] ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ ($filters['marital_status'] ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Education</label>
                            <select name="education_id" class="form-select">
                                <option value="">Any Education</option>
                                @foreach($educations ?? [] as $edu)
                                    <option value="{{ $edu->id }}" {{ ($filters['education_id'] ?? '') == $edu->id ? 'selected' : '' }}>
                                        {{ $edu->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Occupation</label>
                            <select name="occupation_id" class="form-select">
                                <option value="">Any Occupation</option>
                                @foreach($occupations ?? [] as $occ)
                                    <option value="{{ $occ->id }}" {{ ($filters['occupation_id'] ?? '') == $occ->id ? 'selected' : '' }}>
                                        {{ $occ->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <select name="country_id" class="form-select">
                                <option value="">Any Country</option>
                                @foreach($countries ?? [] as $country)
                                    <option value="{{ $country->id }}" {{ ($filters['country_id'] ?? '') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Update Search
                        </button>
                        
                        <a href="{{ route('search.index') }}" class="btn btn-outline btn-block mt-2">
                            Clear All
                        </a>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="lg:col-span-3">
                @if(isset($results) && count($results) > 0)
                    <div class="mb-4 flex justify-between items-center">
                        <p class="text-muted">Showing {{ $results->firstItem() }}-{{ $results->lastItem() }} of {{ $results->total() }} profiles</p>
                        <select class="form-select" style="width: auto;" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => 'desc']) }}" 
                                    {{ ($filters['sort_by'] ?? '') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort_by' => 'last_login_at', 'sort_order' => 'desc']) }}" 
                                    {{ ($filters['sort_by'] ?? '') == 'last_login_at' ? 'selected' : '' }}>Last Active</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($results as $profile)
                            @include('components.profile-card', ['profile' => $profile])
                        @endforeach
                    </div>
                    
                    @if($results->hasPages())
                        <div class="mt-6">
                            {{ $results->withQueryString()->links() }}
                        </div>
                    @endif
                @else
                    <div class="card p-8 text-center">
                        <i class="fas fa-search fa-4x text-muted mb-4" style="display: block;"></i>
                        <h3>No Profiles Found</h3>
                        <p class="text-muted mb-4">Try adjusting your search criteria to find more matches.</p>
                        <a href="{{ route('search.index') }}" class="btn btn-primary">
                            <i class="fas fa-sliders-h"></i> Modify Search
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
