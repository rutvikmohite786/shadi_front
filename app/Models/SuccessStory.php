<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuccessStory extends Model
{
    protected $fillable = [
        'bride_id', 'groom_id', 'title', 'story',
        'photo_path', 'wedding_date', 'is_approved', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function bride(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bride_id');
    }

    public function groom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'groom_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo_path ? asset('images/static/' . $this->photo_path) : null;
    }
}











