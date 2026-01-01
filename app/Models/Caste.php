<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caste extends Model
{
    protected $fillable = ['religion_id', 'name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function religion(): BelongsTo
    {
        return $this->belongsTo(Religion::class);
    }

    public function subcastes(): HasMany
    {
        return $this->hasMany(Subcaste::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}



















