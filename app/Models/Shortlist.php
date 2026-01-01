<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shortlist extends Model
{
    protected $fillable = [
        'user_id',
        'shortlisted_user_id',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shortlistedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shortlisted_user_id');
    }
}



















