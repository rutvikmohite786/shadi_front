@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
<style>
    /* Profile edit page - Ensure correct layout: Navbar at top, Footer at bottom */
    
    /* Navbar positioning - must stay at top */
    #app > nav.navbar {
        position: sticky !important;
        top: 0 !important;
        z-index: 1000 !important;
        width: 100% !important;
    }
    
    /* Main content - normal flow after navbar */
    #app > main {
        position: relative;
        z-index: 1;
    }
    
    /* Footer - normal flow after main, at bottom */
    #app > footer.footer {
        position: relative;
        z-index: 1;
        margin-top: 0;
    }
    
    /* Profile edit content */
    .py-8 {
        position: relative;
    }
    
    @media (max-width: 768px) {
        /* Mobile: navbar fixed at top */
        #app > nav.navbar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
        }
        
        /* Add space for fixed navbar */
        #app > main {
            margin-top: 60px !important;
            padding-bottom: 80px !important;
        }
        
        .py-8 {
            padding-top: 1rem !important;
            padding-bottom: 100px !important;
        }
        
        /* Hide footer on mobile */
        #app > footer.footer {
            display: none !important;
        }
    }
    
    @media (min-width: 769px) {
        /* Desktop: navbar sticky at top */
        #app > nav.navbar {
            position: sticky !important;
            top: 0 !important;
        }
        
        #app > main {
            margin-top: 0 !important;
        }
        
        .py-8 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }
        
        /* Ensure footer shows on desktop */
        #app > footer.footer {
            display: block !important;
        }
    }
    
    /* Ensure profile photo preview displays correctly */
    #profile-photo-preview {
        display: block;
        background: var(--gray-100);
        min-height: 150px;
    }
    
    #profile-photo-preview[src=""],
    #profile-photo-preview:not([src]) {
        background-image: url('{{ asset("images/static/default-" . (auth()->user()->gender === "female" ? "female" : "male") . ".jpg") }}');
        background-size: cover;
        background-position: center;
    }
</style>
@endpush

@section('content')
<div class="py-8">
    <div class="container">
        <div class="dashboard-header mb-6">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                <div>
                    <h1>Edit Your Profile</h1>
                    <p>Complete your profile to get better matches</p>
                </div>
                <a href="{{ route('profile.biodata.download.pdf') }}" class="btn btn-secondary btn-sm" aria-label="Download your biodata as PDF" tabindex="0">
                    <i class="fas fa-file-download"></i> Download Bio Data (PDF)
                </a>
            </div>
        </div>

        <!-- Profile Completion Progress -->
        <div class="card p-4 mb-6">
            <div class="flex items-center gap-4">
                <img src="{{ $user->getProfilePhotoUrl() }}" alt="{{ $user->name }}" 
                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                <div class="flex-1">
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">Profile ID: {{ $user->id }}</p>
                    @php
                        $completionPercentage = $user->getProfileCompletionPercentage();
                    @endphp
                    <div class="flex items-center gap-3">
                        <div style="flex: 1; height: 8px; background: var(--gray-200); border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $completionPercentage }}%; height: 100%; background: linear-gradient(90deg, var(--primary-500), var(--primary-600)); border-radius: 4px;"></div>
                        </div>
                        <span style="font-weight: 600;">{{ $completionPercentage }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="card mb-6" style="overflow: visible;">
            <div class="flex flex-wrap gap-2 p-4" style="border-bottom: 1px solid var(--gray-200);">
                <a href="#basic" class="btn btn-primary btn-sm" onclick="showTab('basic')">
                    <i class="fas fa-user"></i> Basic Info
                </a>
                <a href="#personal" class="btn btn-outline btn-sm" onclick="showTab('personal')">
                    <i class="fas fa-id-card"></i> Personal Details
                </a>
                <a href="#religious" class="btn btn-outline btn-sm" onclick="showTab('religious')">
                    <i class="fas fa-om"></i> Religious Background
                </a>
                <a href="#location" class="btn btn-outline btn-sm" onclick="showTab('location')">
                    <i class="fas fa-map-marker-alt"></i> Location
                </a>
                <a href="#education" class="btn btn-outline btn-sm" onclick="showTab('education')">
                    <i class="fas fa-graduation-cap"></i> Education & Career
                </a>
                <a href="#family" class="btn btn-outline btn-sm" onclick="showTab('family')">
                    <i class="fas fa-users"></i> Family Details
                </a>
                <a href="#partner" class="btn btn-outline btn-sm" onclick="showTab('partner')">
                    <i class="fas fa-heart"></i> Partner Preferences
                </a>
                <a href="#photos" class="btn btn-outline btn-sm" onclick="showTab('photos')">
                    <i class="fas fa-camera"></i> Photos
                </a>
            </div>

            <!-- Basic Info Tab -->
            <div id="tab-basic" class="tab-content p-6">
                <h3 class="mb-4"><i class="fas fa-user text-primary"></i> Basic Information</h3>
                <form action="{{ route('profile.basic-info') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <div class="form-text">Email cannot be changed</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" value="{{ $user->dob?->format('Y-m-d') }}" disabled>
                            <div class="form-text">Date of birth cannot be changed</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="gender" class="form-label">Gender</label>
                            <input type="text" class="form-control" value="{{ ucfirst($user->gender) }}" disabled>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Basic Info
                    </button>
                </form>
            </div>

            <!-- Personal Details Tab -->
            <div id="tab-personal" class="tab-content p-6" style="display: none;">
                <h3 class="mb-4"><i class="fas fa-id-card text-primary"></i> Personal Details</h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label for="height" class="form-label">Height (in cm)</label>
                            <input type="number" id="height" name="height" class="form-control" 
                                   value="{{ old('height', $profile?->height) }}" step="0.01" min="120" max="220">
                        </div>
                        
                        <div class="form-group">
                            <label for="weight" class="form-label">Weight (in kg)</label>
                            <input type="number" id="weight" name="weight" class="form-control" 
                                   value="{{ old('weight', $profile?->weight) }}" step="0.01" min="30" max="200">
                        </div>
                        
                        <div class="form-group">
                            <label for="body_type" class="form-label">Body Type</label>
                            <select id="body_type" name="body_type" class="form-select">
                                <option value="">Select</option>
                                @foreach(['slim', 'average', 'athletic', 'heavy'] as $type)
                                    <option value="{{ $type }}" {{ old('body_type', $profile?->body_type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="complexion" class="form-label">Complexion</label>
                            <select id="complexion" name="complexion" class="form-select">
                                <option value="">Select</option>
                                @foreach(['very_fair', 'fair', 'wheatish', 'dark'] as $type)
                                    <option value="{{ $type }}" {{ old('complexion', $profile?->complexion) == $type ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', ucfirst($type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="physical_status" class="form-label">Physical Status</label>
                            <select id="physical_status" name="physical_status" class="form-select">
                                <option value="">Select</option>
                                <option value="normal" {{ old('physical_status', $profile?->physical_status) == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="physically_challenged" {{ old('physical_status', $profile?->physical_status) == 'physically_challenged' ? 'selected' : '' }}>Physically Challenged</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="marital_status" class="form-label">Marital Status *</label>
                            <select id="marital_status" name="marital_status" class="form-select" required>
                                <option value="">Select</option>
                                @foreach(['never_married' => 'Never Married', 'divorced' => 'Divorced', 'widowed' => 'Widowed', 'awaiting_divorce' => 'Awaiting Divorce'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('marital_status', $profile?->marital_status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="diet" class="form-label">Diet</label>
                            <select id="diet" name="diet" class="form-select">
                                <option value="">Select</option>
                                @foreach(['vegetarian', 'non_vegetarian', 'eggetarian', 'vegan'] as $type)
                                    <option value="{{ $type }}" {{ old('diet', $profile?->diet) == $type ? 'selected' : '' }}>
                                        {{ str_replace('_', '-', ucfirst($type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="smoke" class="form-label">Smoking</label>
                            <select id="smoke" name="smoke" class="form-select">
                                <option value="">Select</option>
                                <option value="no" {{ old('smoke', $profile?->smoke) == 'no' ? 'selected' : '' }}>No</option>
                                <option value="occasionally" {{ old('smoke', $profile?->smoke) == 'occasionally' ? 'selected' : '' }}>Occasionally</option>
                                <option value="yes" {{ old('smoke', $profile?->smoke) == 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="drink" class="form-label">Drinking</label>
                            <select id="drink" name="drink" class="form-select">
                                <option value="">Select</option>
                                <option value="no" {{ old('drink', $profile?->drink) == 'no' ? 'selected' : '' }}>No</option>
                                <option value="occasionally" {{ old('drink', $profile?->drink) == 'occasionally' ? 'selected' : '' }}>Occasionally</option>
                                <option value="yes" {{ old('drink', $profile?->drink) == 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <label for="about_me" class="form-label">About Me</label>
                        <textarea id="about_me" name="about_me" class="form-control" rows="4" 
                                  placeholder="Tell us about yourself, your hobbies, interests, and what you're looking for...">{{ old('about_me', $profile?->about_me) }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-4">
                        <i class="fas fa-save"></i> Save Personal Details
                    </button>
                </form>
            </div>

            <!-- Religious Background Tab -->
            <div id="tab-religious" class="tab-content p-6" style="display: none;">
                <h3 class="mb-4"><i class="fas fa-om text-primary"></i> Religious Background</h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="religion_id" class="form-label">Religion *</label>
                            <select id="religion_id" name="religion_id" class="form-select" required>
                                <option value="">Select Religion</option>
                                @foreach(\App\Models\Religion::all() as $religion)
                                    <option value="{{ $religion->id }}" {{ old('religion_id', $profile?->religion_id) == $religion->id ? 'selected' : '' }}>
                                        {{ $religion->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="caste_id" class="form-label">Caste</label>
                            <select id="caste_id" name="caste_id" class="form-select" data-selected="{{ old('caste_id', $profile?->caste_id) }}">
                                <option value="">Select Caste</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="subcaste_id" class="form-label">Sub-Caste</label>
                            <select id="subcaste_id" name="subcaste_id" class="form-select" data-selected="{{ old('subcaste_id', $profile?->subcaste_id) }}">
                                <option value="">Select Sub-Caste</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="mother_tongue_id" class="form-label">Mother Tongue</label>
                            <select id="mother_tongue_id" name="mother_tongue_id" class="form-select">
                                <option value="">Select Mother Tongue</option>
                                @foreach(\App\Models\MotherTongue::all() as $tongue)
                                    <option value="{{ $tongue->id }}" {{ old('mother_tongue_id', $profile?->mother_tongue_id) == $tongue->id ? 'selected' : '' }}>
                                        {{ $tongue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="gothra" class="form-label">Gothra</label>
                            <input type="text" id="gothra" name="gothra" class="form-control" 
                                   value="{{ old('gothra', $profile?->gothra) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="manglik" class="form-label">Manglik</label>
                            <select id="manglik" name="manglik" class="form-select">
                                <option value="">Select</option>
                                <option value="1" {{ old('manglik', $profile?->manglik) == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('manglik', $profile?->manglik) == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="star" class="form-label">Star / Nakshatra</label>
                            <input type="text" id="star" name="star" class="form-control" 
                                   value="{{ old('star', $profile?->star) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="raasi" class="form-label">Raasi</label>
                            <input type="text" id="raasi" name="raasi" class="form-control" 
                                   value="{{ old('raasi', $profile?->raasi) }}">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-4">
                        <i class="fas fa-save"></i> Save Religious Details
                    </button>
                </form>
            </div>

            <!-- Location Tab -->
            <div id="tab-location" class="tab-content p-6" style="display: none;">
                <h3 class="mb-4"><i class="fas fa-map-marker-alt text-primary"></i> Location Details</h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="country_id" class="form-label">Country *</label>
                            <select id="country_id" name="country_id" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach(\App\Models\Country::all() as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id', $profile?->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="state_id" class="form-label">State</label>
                            <select id="state_id" name="state_id" class="form-select" data-selected="{{ old('state_id', $profile?->state_id) }}">
                                <option value="">Select State</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="city_id" class="form-label">City</label>
                            <select id="city_id" name="city_id" class="form-select" data-selected="{{ old('city_id', $profile?->city_id) }}">
                                <option value="">Select City</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="citizenship" class="form-label">Citizenship</label>
                            <input type="text" id="citizenship" name="citizenship" class="form-control" 
                                   value="{{ old('citizenship', $profile?->citizenship) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="residing_country" class="form-label">Residing Country</label>
                            <input type="text" id="residing_country" name="residing_country" class="form-control" 
                                   value="{{ old('residing_country', $profile?->residing_country) }}">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-4">
                        <i class="fas fa-save"></i> Save Location Details
                    </button>
                </form>
            </div>

            <!-- Education & Career Tab -->
            <div id="tab-education" class="tab-content p-6" style="display: none;">
                <h3 class="mb-4"><i class="fas fa-graduation-cap text-primary"></i> Education & Career</h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="education_id" class="form-label">Highest Education *</label>
                            <select id="education_id" name="education_id" class="form-select" required>
                                <option value="">Select Education</option>
                                @foreach(\App\Models\Education::all() as $edu)
                                    <option value="{{ $edu->id }}" {{ old('education_id', $profile?->education_id) == $edu->id ? 'selected' : '' }}>
                                        {{ $edu->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="education_detail" class="form-label">Education Details</label>
                            <input type="text" id="education_detail" name="education_detail" class="form-control" 
                                   value="{{ old('education_detail', $profile?->education_detail) }}" 
                                   placeholder="e.g., B.Tech in Computer Science">
                        </div>
                        
                        <div class="form-group">
                            <label for="occupation_id" class="form-label">Occupation *</label>
                            <select id="occupation_id" name="occupation_id" class="form-select" required>
                                <option value="">Select Occupation</option>
                                @foreach(\App\Models\Occupation::all() as $occ)
                                    <option value="{{ $occ->id }}" {{ old('occupation_id', $profile?->occupation_id) == $occ->id ? 'selected' : '' }}>
                                        {{ $occ->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="occupation_detail" class="form-label">Job Title / Designation</label>
                            <input type="text" id="occupation_detail" name="occupation_detail" class="form-control" 
                                   value="{{ old('occupation_detail', $profile?->occupation_detail) }}" 
                                   placeholder="e.g., Software Engineer">
                        </div>
                        
                        <div class="form-group">
                            <label for="employer_name" class="form-label">Employer / Company</label>
                            <input type="text" id="employer_name" name="employer_name" class="form-control" 
                                   value="{{ old('employer_name', $profile?->employer_name) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="annual_income" class="form-label">Annual Income</label>
                            <select id="annual_income" name="annual_income" class="form-select">
                                <option value="">Select</option>
                                @foreach(['below_2l' => 'Below ₹2 Lakh', '2l_5l' => '₹2-5 Lakh', '5l_10l' => '₹5-10 Lakh', '10l_20l' => '₹10-20 Lakh', '20l_50l' => '₹20-50 Lakh', 'above_50l' => 'Above ₹50 Lakh'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('annual_income', $profile?->annual_income) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-4">
                        <i class="fas fa-save"></i> Save Education & Career
                    </button>
                </form>
            </div>

            <!-- Family Details Tab -->
            <div id="tab-family" class="tab-content p-6" style="display: none;">
                <h3 class="mb-4"><i class="fas fa-users text-primary"></i> Family Details</h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label for="family_type" class="form-label">Family Type</label>
                            <select id="family_type" name="family_type" class="form-select">
                                <option value="">Select</option>
                                <option value="joint" {{ old('family_type', $profile?->family_type) == 'joint' ? 'selected' : '' }}>Joint Family</option>
                                <option value="nuclear" {{ old('family_type', $profile?->family_type) == 'nuclear' ? 'selected' : '' }}>Nuclear Family</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="family_status" class="form-label">Family Status</label>
                            <select id="family_status" name="family_status" class="form-select">
                                <option value="">Select</option>
                                @foreach(['middle_class' => 'Middle Class', 'upper_middle' => 'Upper Middle Class', 'rich' => 'Rich', 'affluent' => 'Affluent'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('family_status', $profile?->family_status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="family_values" class="form-label">Family Values</label>
                            <select id="family_values" name="family_values" class="form-select">
                                <option value="">Select</option>
                                <option value="traditional" {{ old('family_values', $profile?->family_values) == 'traditional' ? 'selected' : '' }}>Traditional</option>
                                <option value="moderate" {{ old('family_values', $profile?->family_values) == 'moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="liberal" {{ old('family_values', $profile?->family_values) == 'liberal' ? 'selected' : '' }}>Liberal</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="father_occupation" class="form-label">Father's Occupation</label>
                            <input type="text" id="father_occupation" name="father_occupation" class="form-control" 
                                   value="{{ old('father_occupation', $profile?->father_occupation) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="mother_occupation" class="form-label">Mother's Occupation</label>
                            <input type="text" id="mother_occupation" name="mother_occupation" class="form-control" 
                                   value="{{ old('mother_occupation', $profile?->mother_occupation) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="num_brothers" class="form-label">No. of Brothers</label>
                            <input type="number" id="num_brothers" name="num_brothers" class="form-control" 
                                   value="{{ old('num_brothers', $profile?->num_brothers) }}" min="0" max="10">
                        </div>
                        
                        <div class="form-group">
                            <label for="brothers_married" class="form-label">Brothers Married</label>
                            <input type="number" id="brothers_married" name="brothers_married" class="form-control" 
                                   value="{{ old('brothers_married', $profile?->brothers_married) }}" min="0" max="10">
                        </div>
                        
                        <div class="form-group">
                            <label for="num_sisters" class="form-label">No. of Sisters</label>
                            <input type="number" id="num_sisters" name="num_sisters" class="form-control" 
                                   value="{{ old('num_sisters', $profile?->num_sisters) }}" min="0" max="10">
                        </div>
                        
                        <div class="form-group">
                            <label for="sisters_married" class="form-label">Sisters Married</label>
                            <input type="number" id="sisters_married" name="sisters_married" class="form-control" 
                                   value="{{ old('sisters_married', $profile?->sisters_married) }}" min="0" max="10">
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <label for="about_family" class="form-label">About Family</label>
                        <textarea id="about_family" name="about_family" class="form-control" rows="3" 
                                  placeholder="Tell us about your family...">{{ old('about_family', $profile?->about_family) }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-4">
                        <i class="fas fa-save"></i> Save Family Details
                    </button>
                </form>
            </div>

            <!-- Partner Preferences Tab -->
            <div id="tab-partner" class="tab-content p-6" style="display: none;">
                <h3 class="mb-4"><i class="fas fa-heart text-primary"></i> Partner Preferences</h3>
                <form action="{{ route('profile.partner-preferences') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="partner_age_min" class="form-label">Age Range (Min)</label>
                            <input type="number" id="partner_age_min" name="partner_age_min" class="form-control" 
                                   value="{{ old('partner_age_min', $profile?->partner_age_min) }}" min="18" max="70">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_age_max" class="form-label">Age Range (Max)</label>
                            <input type="number" id="partner_age_max" name="partner_age_max" class="form-control" 
                                   value="{{ old('partner_age_max', $profile?->partner_age_max) }}" min="18" max="70">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_height_min" class="form-label">Height Min (cm)</label>
                            <input type="number" id="partner_height_min" name="partner_height_min" class="form-control" 
                                   value="{{ old('partner_height_min', $profile?->partner_height_min) }}" step="0.01" min="120" max="220">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_height_max" class="form-label">Height Max (cm)</label>
                            <input type="number" id="partner_height_max" name="partner_height_max" class="form-control" 
                                   value="{{ old('partner_height_max', $profile?->partner_height_max) }}" step="0.01" min="120" max="220">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_marital_status" class="form-label">Marital Status</label>
                            <input type="text" id="partner_marital_status" name="partner_marital_status" class="form-control" 
                                   value="{{ old('partner_marital_status', $profile?->partner_marital_status) }}" 
                                   placeholder="e.g., Never Married, Divorced">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_religion" class="form-label">Preferred Religion</label>
                            <input type="text" id="partner_religion" name="partner_religion" class="form-control" 
                                   value="{{ old('partner_religion', $profile?->partner_religion) }}" 
                                   placeholder="e.g., Hindu, Any">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_caste" class="form-label">Preferred Caste</label>
                            <input type="text" id="partner_caste" name="partner_caste" class="form-control" 
                                   value="{{ old('partner_caste', $profile?->partner_caste) }}" 
                                   placeholder="e.g., Brahmin, Any">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_education" class="form-label">Preferred Education</label>
                            <input type="text" id="partner_education" name="partner_education" class="form-control" 
                                   value="{{ old('partner_education', $profile?->partner_education) }}" 
                                   placeholder="e.g., Graduate, Post Graduate">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_occupation" class="form-label">Preferred Occupation</label>
                            <input type="text" id="partner_occupation" name="partner_occupation" class="form-control" 
                                   value="{{ old('partner_occupation', $profile?->partner_occupation) }}" 
                                   placeholder="e.g., Private Job, Business">
                        </div>
                        
                        <div class="form-group">
                            <label for="partner_country" class="form-label">Preferred Country</label>
                            <input type="text" id="partner_country" name="partner_country" class="form-control" 
                                   value="{{ old('partner_country', $profile?->partner_country) }}" 
                                   placeholder="e.g., India, USA">
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <label for="partner_expectations" class="form-label">Partner Expectations</label>
                        <textarea id="partner_expectations" name="partner_expectations" class="form-control" rows="4" 
                                  placeholder="Describe your ideal partner...">{{ old('partner_expectations', $profile?->partner_expectations) }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-4">
                        <i class="fas fa-save"></i> Save Partner Preferences
                    </button>
                </form>
            </div>

            <!-- Photos Tab -->
            <div id="tab-photos" class="tab-content p-6" style="display: none;">
                <h3 class="mb-4"><i class="fas fa-camera text-primary"></i> Profile Photos</h3>
                
                <!-- Upload New Photo -->
                <div class="card p-4 mb-6" style="background: var(--gray-50);">
                    <h5 class="mb-3">Upload Photo</h5>
                    <form action="{{ route('profile.photo.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="photo" class="form-label">Select Photo</label>
                                <input type="file" id="photo" name="photo" class="form-control" accept="image/*" required>
                                <div class="form-text">Max size: 5MB. Allowed: JPG, PNG, WEBP</div>
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-label">Photo Type</label>
                                <select id="type" name="type" class="form-select">
                                    <option value="profile">Profile Photo</option>
                                    <option value="gallery">Gallery Photo</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-upload"></i> Upload Photo
                        </button>
                    </form>
                </div>
                
                <!-- Current Photos -->
                <h5 class="mb-3">Your Photos</h5>
                @if($photos && count($photos) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($photos as $photo)
                            <div class="card" style="position: relative;">
                                <img src="{{ $photo->getPhotoUrl() }}" alt="Photo" 
                                     style="display: block; width: 90%; height: 340px; object-fit: cover; margin: 0 auto;"
                                     onerror="this.src='{{ asset('images/static/default-' . (auth()->user()->gender === 'female' ? 'female' : 'male') . '.jpg') }}'; this.onerror=null;">
                                @if($photo->is_primary)
                                    <span class="badge badge-primary" style="position: absolute; top: 10px; left: 10px;">Primary</span>
                                @endif
                                @if(!$photo->is_approved)
                                    <span class="badge badge-secondary" style="position: absolute; top: 10px; right: 10px;">Pending</span>
                                @endif
                                <div class="p-2 flex gap-2">
                                    @if(!$photo->is_primary)
                                        <form action="{{ route('profile.photo.primary', $photo->id) }}" method="POST" style="flex: 1;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline btn-sm btn-block">Set Primary</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('profile.photo.delete', $photo->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: var(--danger);" 
                                                onclick="return confirm('Delete this photo?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8" style="background: var(--gray-50); border-radius: var(--radius-lg);">
                        <i class="fas fa-images fa-3x text-muted mb-3" style="display: block;"></i>
                        <p class="text-muted">No photos uploaded yet. Add photos to get more profile views!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(function(tab) {
            tab.style.display = 'none';
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('[onclick^="showTab"]').forEach(function(btn) {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline');
        });
        
        // Show selected tab
        document.getElementById('tab-' + tabName).style.display = 'block';
        
        // Add active class to clicked button
        event.target.classList.remove('btn-outline');
        event.target.classList.add('btn-primary');
        
        // Update URL hash
        window.location.hash = tabName;
    }
    
    // Show tab based on URL hash on page load
    document.addEventListener('DOMContentLoaded', function() {
        var hash = window.location.hash.replace('#', '');
        if (hash && document.getElementById('tab-' + hash)) {
            showTab(hash);
        }
    });
    
    // Dependent dropdowns for Religion -> Caste -> Subcaste
    function loadCastes(religionId, selectedCasteId = null) {
        var casteSelect = document.getElementById('caste_id');
        if (!casteSelect) return Promise.resolve();
        
        if (religionId) {
            return fetch('/ajax/castes/' + religionId)
                .then(response => response.json())
                .then(data => {
                    casteSelect.innerHTML = '<option value="">Select Caste</option>';
                    data.forEach(function(caste) {
                        var selected = selectedCasteId && caste.id == selectedCasteId ? ' selected' : '';
                        casteSelect.innerHTML += '<option value="' + caste.id + '"' + selected + '>' + caste.name + '</option>';
                    });
                });
        } else {
            casteSelect.innerHTML = '<option value="">Select Caste</option>';
            return Promise.resolve();
        }
    }
    
    function loadSubcastes(casteId, selectedSubcasteId = null) {
        var subcasteSelect = document.getElementById('subcaste_id');
        if (!subcasteSelect) return Promise.resolve();
        
        if (casteId) {
            return fetch('/ajax/subcastes/' + casteId)
                .then(response => response.json())
                .then(data => {
                    subcasteSelect.innerHTML = '<option value="">Select Sub-Caste</option>';
                    data.forEach(function(subcaste) {
                        var selected = selectedSubcasteId && subcaste.id == selectedSubcasteId ? ' selected' : '';
                        subcasteSelect.innerHTML += '<option value="' + subcaste.id + '"' + selected + '>' + subcaste.name + '</option>';
                    });
                });
        } else {
            subcasteSelect.innerHTML = '<option value="">Select Sub-Caste</option>';
            return Promise.resolve();
        }
    }
    
    // Load castes/subcastes on page load if religion/caste is already selected
    (function() {
        var religionSelect = document.getElementById('religion_id');
        var casteSelect = document.getElementById('caste_id');
        var subcasteSelect = document.getElementById('subcaste_id');
        
        if (religionSelect && religionSelect.value) {
            var savedCasteId = casteSelect ? casteSelect.getAttribute('data-selected') : null;
            var savedSubcasteId = subcasteSelect ? subcasteSelect.getAttribute('data-selected') : null;
            
            loadCastes(religionSelect.value, savedCasteId).then(function() {
                if (savedCasteId) {
                    loadSubcastes(savedCasteId, savedSubcasteId);
                }
            });
        }
    })();
    
    document.getElementById('religion_id')?.addEventListener('change', function() {
        var religionId = this.value;
        loadCastes(religionId);
        // Clear subcaste when religion changes
        var subcasteSelect = document.getElementById('subcaste_id');
        if (subcasteSelect) {
            subcasteSelect.innerHTML = '<option value="">Select Sub-Caste</option>';
        }
    });
    
    document.getElementById('caste_id')?.addEventListener('change', function() {
        var casteId = this.value;
        loadSubcastes(casteId);
    });
    
    // Dependent dropdowns for Country -> State -> City
    function loadStates(countryId, selectedStateId = null) {
        var stateSelect = document.getElementById('state_id');
        if (!stateSelect) return Promise.resolve();
        
        if (countryId) {
            return fetch('/ajax/states/' + countryId)
                .then(response => response.json())
                .then(data => {
                    stateSelect.innerHTML = '<option value="">Select State</option>';
                    data.forEach(function(state) {
                        var selected = selectedStateId && state.id == selectedStateId ? ' selected' : '';
                        stateSelect.innerHTML += '<option value="' + state.id + '"' + selected + '>' + state.name + '</option>';
                    });
                });
        } else {
            stateSelect.innerHTML = '<option value="">Select State</option>';
            return Promise.resolve();
        }
    }
    
    function loadCities(stateId, selectedCityId = null) {
        var citySelect = document.getElementById('city_id');
        if (!citySelect) return Promise.resolve();
        
        if (stateId) {
            return fetch('/ajax/cities/' + stateId)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    data.forEach(function(city) {
                        var selected = selectedCityId && city.id == selectedCityId ? ' selected' : '';
                        citySelect.innerHTML += '<option value="' + city.id + '"' + selected + '>' + city.name + '</option>';
                    });
                });
        } else {
            citySelect.innerHTML = '<option value="">Select City</option>';
            return Promise.resolve();
        }
    }
    
    // Load states/cities on page load if country/state is already selected
    (function() {
        var countrySelect = document.getElementById('country_id');
        var stateSelect = document.getElementById('state_id');
        var citySelect = document.getElementById('city_id');
        
        if (countrySelect && countrySelect.value) {
            var savedStateId = stateSelect ? stateSelect.getAttribute('data-selected') : null;
            var savedCityId = citySelect ? citySelect.getAttribute('data-selected') : null;
            
            loadStates(countrySelect.value, savedStateId).then(function() {
                if (savedStateId) {
                    loadCities(savedStateId, savedCityId);
                }
            });
        }
    })();
    
    document.getElementById('country_id')?.addEventListener('change', function() {
        var countryId = this.value;
        loadStates(countryId);
        // Clear city when country changes
        var citySelect = document.getElementById('city_id');
        if (citySelect) {
            citySelect.innerHTML = '<option value="">Select City</option>';
        }
    });
    
    document.getElementById('state_id')?.addEventListener('change', function() {
        var stateId = this.value;
        loadCities(stateId);
    });
</script>
@endpush
@endsection

