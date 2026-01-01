<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'user_id',
        'matched_user_id',
        'match_score',
        'is_mutual',
        'matched_date',
    ];

    protected function casts(): array
    {
        return [
            'match_score' => 'decimal:2',
            'is_mutual' => 'boolean',
            'matched_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function matchedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }

    public function scopeMutual($query)
    {
        return $query->where('is_mutual', true);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('matched_date', today());
    }
}



















