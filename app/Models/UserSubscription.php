<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'start_date', 'end_date',
        'contact_views_used', 'chats_used', 'interests_sent',
        'status', 'payment_reference',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'contact_views_used' => 'integer',
            'chats_used' => 'integer',
            'interests_sent' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('end_date', '>=', now());
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now();
    }

    public function isExpired(): bool
    {
        return $this->end_date < now();
    }

    public function daysRemaining(): int
    {
        return $this->isExpired() ? 0 : now()->diffInDays($this->end_date);
    }

    public function canViewMoreContacts(): bool
    {
        return $this->plan->isUnlimitedContacts() || $this->contact_views_used < $this->plan->contact_views_limit;
    }

    public function canSendMoreInterests(): bool
    {
        return $this->plan->isUnlimitedInterests() || $this->interests_sent < $this->plan->interest_limit;
    }

    public function canChatMore(): bool
    {
        return $this->plan->isUnlimitedChats() || $this->chats_used < $this->plan->chat_limit;
    }

    public function getRemainingContacts(): int|string
    {
        return $this->plan->isUnlimitedContacts() ? 'Unlimited' : max(0, $this->plan->contact_views_limit - $this->contact_views_used);
    }

    public function getRemainingInterests(): int|string
    {
        return $this->plan->isUnlimitedInterests() ? 'Unlimited' : max(0, $this->plan->interest_limit - $this->interests_sent);
    }

    public function getRemainingChats(): int|string
    {
        return $this->plan->isUnlimitedChats() ? 'Unlimited' : max(0, $this->plan->chat_limit - $this->chats_used);
    }
}















