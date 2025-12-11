<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPhoto extends Model
{
    protected $fillable = [
        'user_id',
        'photo_path',
        'photo_type',
        'is_approved',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeGallery($query)
    {
        return $query->where('photo_type', 'gallery');
    }

    public function getPhotoUrl(): string
    {
        return asset('images/gallery/' . $this->photo_path);
    }
}



