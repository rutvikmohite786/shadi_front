<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'gender',
        'dob',
        'role',
        'profile_photo',
        'photo_privacy',
        'is_active',
        'is_verified',
        'profile_completed',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // Password is manually hashed in UserService, so we don't use 'hashed' cast here
            'dob' => 'date',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'profile_completed' => 'boolean',
        ];
    }

    // Relationships
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UserPhoto::class);
    }

    public function sentInterests(): HasMany
    {
        return $this->hasMany(Interest::class, 'sender_id');
    }

    public function receivedInterests(): HasMany
    {
        return $this->hasMany(Interest::class, 'receiver_id');
    }

    public function shortlists(): HasMany
    {
        return $this->hasMany(Shortlist::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(UserMatch::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->where('end_date', '>=', now());
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class, 'viewed_id');
    }

    public function contactViews(): HasMany
    {
        return $this->hasMany(ContactView::class, 'viewer_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeProfileCompleted($query)
    {
        return $query->where('profile_completed', true);
    }

    public function scopeMale($query)
    {
        return $query->where('gender', 'male');
    }

    public function scopeFemale($query)
    {
        return $query->where('gender', 'female');
    }

    // Helpers
    public function getAge(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }

    public function getProfilePhotoUrl(): string
    {
        // First, check if user has a primary photo set in UserPhoto table (this takes priority)
        if ($this->photos()->exists()) {
            $primaryPhoto = $this->photos()->where('is_primary', true)->first();
            if ($primaryPhoto) {
                // Use the getPhotoUrl method which handles the correct path based on photo_type
                return $primaryPhoto->getPhotoUrl();
            }
        }
        
        // Check if user has uploaded profile photo (from users.profile_photo field)
        if ($this->profile_photo) {
            return asset('images/profile/' . $this->profile_photo);
        }
        
        // Check if user has any photos uploaded (in UserPhoto table) - get approved or any photo
        if ($this->photos()->exists()) {
            $approvedPhoto = $this->photos()->where('is_approved', true)->first();
            if ($approvedPhoto) {
                return $approvedPhoto->getPhotoUrl();
            }
            
            // If no approved photo, get any photo
            $anyPhoto = $this->photos()->first();
            if ($anyPhoto) {
                return $anyPhoto->getPhotoUrl();
            }
        }
        
        // If no photos uploaded at all, show static images based on gender
        return $this->gender === 'female' 
            ? asset('images/static/default-female.jpg')
            : asset('images/static/default-male.jpg');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription !== null;
    }

    /**
     * Calculate profile completion percentage based on filled fields
     */
    public function getProfileCompletionPercentage(): int
    {
        $totalFields = 0;
        $filledFields = 0;

        // User basic fields (weight: 30%)
        $userFields = [
            'name' => 5,
            'email' => 5,
            'phone' => 5,
            'gender' => 5,
            'dob' => 5,
            'profile_photo' => 5,
        ];

        foreach ($userFields as $field => $weight) {
            $totalFields += $weight;
            if (!empty($this->$field)) {
                $filledFields += $weight;
            }
        }

        // Profile fields (weight: 70%)
        $profile = $this->profile;
        if ($profile) {
            $profileFields = [
                // Basic Info (20%)
                'marital_status' => 5,
                'height' => 3,
                'about_me' => 4,
                'religion_id' => 4,
                'caste_id' => 2,
                'mother_tongue_id' => 2,
                
                // Location (15%)
                'country_id' => 5,
                'state_id' => 5,
                'city_id' => 5,
                
                // Education & Career (15%)
                'education_id' => 8,
                'occupation_id' => 5,
                'annual_income' => 2,
                
                // Lifestyle (10%)
                'diet' => 3,
                'smoke' => 2,
                'drink' => 2,
                'physical_status' => 3,
                
                // Family (10%)
                'family_type' => 3,
                'family_status' => 2,
                'father_occupation' => 2,
                'mother_occupation' => 2,
                'about_family' => 1,
            ];

            foreach ($profileFields as $field => $weight) {
                $totalFields += $weight;
                $value = $profile->$field;
                // Check if field is filled (not null and not empty string)
                // For numeric fields, we check if value is greater than 0 (0 usually means not set)
                // For string fields, any non-empty value counts
                if ($value !== null && $value !== '') {
                    if (is_numeric($value)) {
                        // For numeric fields, only count if > 0 (0 means not set for most fields)
                        if ($value > 0) {
                            $filledFields += $weight;
                        }
                    } else {
                        // For string fields, any non-empty value counts
                        $filledFields += $weight;
                    }
                }
            }
        }

        if ($totalFields === 0) {
            return 0;
        }

        return min(100, (int) round(($filledFields / $totalFields) * 100));
    }
}
