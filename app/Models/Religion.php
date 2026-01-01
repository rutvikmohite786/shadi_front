<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Religion extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function castes(): HasMany
    {
        return $this->hasMany(Caste::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}



















