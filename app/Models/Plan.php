<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'duration_days',
        'contact_views_limit', 'chat_limit', 'interest_limit',
        'can_see_contact', 'can_chat', 'profile_highlighter',
        'priority_support', 'is_featured', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
            'contact_views_limit' => 'integer',
            'chat_limit' => 'integer',
            'interest_limit' => 'integer',
            'can_see_contact' => 'boolean',
            'can_chat' => 'boolean',
            'profile_highlighter' => 'boolean',
            'priority_support' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function isUnlimitedContacts(): bool
    {
        return $this->contact_views_limit === 0;
    }

    public function isUnlimitedChats(): bool
    {
        return $this->chat_limit === 0;
    }

    public function isUnlimitedInterests(): bool
    {
        return $this->interest_limit === 0;
    }

    public function getFormattedPrice(): string
    {
        return '₹' . number_format($this->price, 0);
    }

    public function getDurationLabel(): string
    {
        if ($this->duration_days <= 30) {
            return $this->duration_days . ' Days';
        }
        $months = round($this->duration_days / 30);
        return $months . ' Month' . ($months > 1 ? 's' : '');
    }
}



