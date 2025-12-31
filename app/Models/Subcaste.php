<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcaste extends Model
{
    protected $fillable = ['caste_id', 'name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function caste(): BelongsTo
    {
        return $this->belongsTo(Caste::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

















