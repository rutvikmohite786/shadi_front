<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartnerPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'partner_age_min' => ['nullable', 'integer', 'min:18', 'max:70'],
            'partner_age_max' => ['nullable', 'integer', 'min:18', 'max:70', 'gte:partner_age_min'],
            'partner_height_min' => ['nullable', 'numeric', 'min:100', 'max:250'],
            'partner_height_max' => ['nullable', 'numeric', 'min:100', 'max:250', 'gte:partner_height_min'],
            'partner_marital_status' => ['nullable', 'string', 'max:255'],
            'partner_religion' => ['nullable', 'string', 'max:255'],
            'partner_caste' => ['nullable', 'string', 'max:255'],
            'partner_mother_tongue' => ['nullable', 'string', 'max:255'],
            'partner_education' => ['nullable', 'string', 'max:255'],
            'partner_occupation' => ['nullable', 'string', 'max:255'],
            'partner_country' => ['nullable', 'string', 'max:255'],
            'partner_state' => ['nullable', 'string', 'max:255'],
            'partner_expectations' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
















