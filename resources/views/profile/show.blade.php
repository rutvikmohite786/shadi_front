@extends('layouts.app')

@section('title', $profile->name . ' - Profile')

@section('content')
<div class="py-8">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Photos & Quick Info -->
            <div>
                <div class="card mb-4">
                    <img src="{{ $profile->getProfilePhotoUrl() }}" alt="{{ $profile->name }}" 
                         style="width: 100%; height: 350px; object-fit: cover;">
                    
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $profile->name }}</h2>
                        <p class="text-muted mb-3">{{ $profile->getAge() }} years old</p>
                        
                        @if($profile->is_verified)
                            <span class="badge badge-success mb-3"><i class="fas fa-check-circle"></i> Verified Profile</span>
                        @endif
                        
                        @if(auth()->id() !== $profile->id)
                            <div class="flex gap-2 mt-4">
                                <form action="{{ route('interests.send', $profile->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-heart"></i> Send Interest
                                    </button>
                                </form>
                                <form action="{{ route('matches.shortlist.add', $profile->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline">
                                        <i class="far fa-star"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="card p-4">
                    <h5 class="mb-3">Quick Info</h5>
                    <ul style="list-style: none;">
                        <li class="py-2" style="border-bottom: 1px solid var(--gray-100);">
                            <i class="fas fa-calendar text-primary" style="width: 24px;"></i>
                            <strong>Age:</strong> {{ $profile->getAge() }} years
                        </li>
                        <li class="py-2" style="border-bottom: 1px solid var(--gray-100);">
                            <i class="fas fa-ruler-vertical text-primary" style="width: 24px;"></i>
                            <strong>Height:</strong> {{ $profile->profile?->getHeightInFeet() ?? 'Not specified' }}
                        </li>
                        <li class="py-2" style="border-bottom: 1px solid var(--gray-100);">
                            <i class="fas fa-ring text-primary" style="width: 24px;"></i>
                            <strong>Marital Status:</strong> {{ $profile->profile?->getMaritalStatusLabel() ?? 'Not specified' }}
                        </li>
                        <li class="py-2" style="border-bottom: 1px solid var(--gray-100);">
                            <i class="fas fa-map-marker-alt text-primary" style="width: 24px;"></i>
                            <strong>Location:</strong> {{ $profile->profile?->city?->name ?? 'Not specified' }}
                        </li>
                        <li class="py-2">
                            <i class="fas fa-om text-primary" style="width: 24px;"></i>
                            <strong>Religion:</strong> {{ $profile->profile?->religion?->name ?? 'Not specified' }}
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Right Column - Detailed Info -->
            <div class="lg:col-span-2">
                <!-- About -->
                @if($profile->profile?->about_me)
                <div class="card p-4 mb-4">
                    <h4 class="mb-3"><i class="fas fa-user text-primary"></i> About {{ $profile->name }}</h4>
                    <p>{{ $profile->profile->about_me }}</p>
                </div>
                @endif
                
                <!-- Basic Details -->
                <div class="card p-4 mb-4">
                    <h4 class="mb-3"><i class="fas fa-id-card text-primary"></i> Basic Details</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-muted">Body Type</span>
                            <p style="font-weight: 500;">{{ ucfirst($profile->profile?->body_type ?? 'Not specified') }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Complexion</span>
                            <p style="font-weight: 500;">{{ ucfirst(str_replace('_', ' ', $profile->profile?->complexion ?? 'Not specified')) }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Diet</span>
                            <p style="font-weight: 500;">{{ ucfirst(str_replace('_', '-', $profile->profile?->diet ?? 'Not specified')) }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Smoking</span>
                            <p style="font-weight: 500;">{{ ucfirst($profile->profile?->smoke ?? 'Not specified') }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Drinking</span>
                            <p style="font-weight: 500;">{{ ucfirst($profile->profile?->drink ?? 'Not specified') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Religious Background -->
                <div class="card p-4 mb-4">
                    <h4 class="mb-3"><i class="fas fa-om text-primary"></i> Religious Background</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-muted">Religion</span>
                            <p style="font-weight: 500;">{{ $profile->profile?->religion?->name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Caste</span>
                            <p style="font-weight: 500;">{{ $profile->profile?->caste?->name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Mother Tongue</span>
                            <p style="font-weight: 500;">{{ $profile->profile?->motherTongue?->name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Manglik</span>
                            <p style="font-weight: 500;">{{ $profile->profile?->manglik ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Education & Career -->
                <div class="card p-4 mb-4">
                    <h4 class="mb-3"><i class="fas fa-graduation-cap text-primary"></i> Education & Career</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-muted">Education</span>
                            <p style="font-weight: 500;">{{ $profile->profile?->education?->name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Occupation</span>
                            <p style="font-weight: 500;">{{ $profile->profile?->occupation?->name ?? 'Not specified' }}</p>
                        </div>
                        @if($profile->profile?->employer_name)
                        <div>
                            <span class="text-muted">Employer</span>
                            <p style="font-weight: 500;">{{ $profile->profile->employer_name }}</p>
                        </div>
                        @endif
                        @if($profile->profile?->annual_income)
                        <div>
                            <span class="text-muted">Annual Income</span>
                            <p style="font-weight: 500;">{{ str_replace('_', ' ', ucfirst($profile->profile->annual_income)) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Family Details -->
                <div class="card p-4 mb-4">
                    <h4 class="mb-3"><i class="fas fa-users text-primary"></i> Family Details</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-muted">Family Type</span>
                            <p style="font-weight: 500;">{{ ucfirst($profile->profile?->family_type ?? 'Not specified') }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Family Status</span>
                            <p style="font-weight: 500;">{{ ucfirst(str_replace('_', ' ', $profile->profile?->family_status ?? 'Not specified')) }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Family Values</span>
                            <p style="font-weight: 500;">{{ ucfirst($profile->profile?->family_values ?? 'Not specified') }}</p>
                        </div>
                    </div>
                    @if($profile->profile?->about_family)
                        <p class="mt-3 text-muted">{{ $profile->profile->about_family }}</p>
                    @endif
                </div>
                
                <!-- Partner Preferences -->
                @if($profile->profile?->partner_expectations)
                <div class="card p-4">
                    <h4 class="mb-3"><i class="fas fa-heart text-primary"></i> Partner Preferences</h4>
                    <p>{{ $profile->profile->partner_expectations }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

