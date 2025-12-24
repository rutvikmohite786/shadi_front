<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'height', 'weight', 'body_type', 'complexion', 'physical_status',
        'marital_status', 'num_children', 'about_me',
        'religion_id', 'caste_id', 'subcaste_id', 'mother_tongue_id',
        'gothra', 'manglik', 'horoscope', 'star', 'raasi',
        'country_id', 'state_id', 'city_id', 'citizenship', 'residing_country',
        'education_id', 'education_detail', 'occupation_id', 'occupation_detail',
        'employer_name', 'annual_income',
        'diet', 'smoke', 'drink',
        'family_type', 'family_status', 'family_values',
        'father_occupation', 'mother_occupation',
        'num_brothers', 'num_sisters', 'brothers_married', 'sisters_married',
        'family_location', 'about_family',
        'partner_age_min', 'partner_age_max', 'partner_height_min', 'partner_height_max',
        'partner_marital_status', 'partner_religion', 'partner_caste',
        'partner_mother_tongue', 'partner_education', 'partner_occupation',
        'partner_country', 'partner_state', 'partner_expectations',
    ];

    protected function casts(): array
    {
        return [
            'height' => 'decimal:2',
            'weight' => 'decimal:2',
            'manglik' => 'boolean',
            'num_children' => 'integer',
            'num_brothers' => 'integer',
            'num_sisters' => 'integer',
            'brothers_married' => 'integer',
            'sisters_married' => 'integer',
            'partner_age_min' => 'integer',
            'partner_age_max' => 'integer',
            'partner_height_min' => 'decimal:2',
            'partner_height_max' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function religion(): BelongsTo
    {
        return $this->belongsTo(Religion::class);
    }

    public function caste(): BelongsTo
    {
        return $this->belongsTo(Caste::class);
    }

    public function subcaste(): BelongsTo
    {
        return $this->belongsTo(Subcaste::class);
    }

    public function motherTongue(): BelongsTo
    {
        return $this->belongsTo(MotherTongue::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function education(): BelongsTo
    {
        return $this->belongsTo(Education::class);
    }

    public function occupation(): BelongsTo
    {
        return $this->belongsTo(Occupation::class);
    }

    public function getHeightInFeet(): ?string
    {
        if (!$this->height) return null;
        $totalInches = $this->height / 2.54;
        $feet = floor($totalInches / 12);
        $inches = round($totalInches % 12);
        return "{$feet}' {$inches}\"";
    }

    public function getMaritalStatusLabel(): ?string
    {
        $labels = [
            'never_married' => 'Never Married',
            'divorced' => 'Divorced',
            'widowed' => 'Widowed',
            'awaiting_divorce' => 'Awaiting Divorce',
        ];
        return $labels[$this->marital_status] ?? null;
    }
}











