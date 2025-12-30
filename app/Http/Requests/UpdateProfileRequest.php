<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'height' => ['nullable', 'numeric', 'min:100', 'max:250'],
            'weight' => ['nullable', 'numeric', 'min:30', 'max:200'],
            'body_type' => ['nullable', 'string', 'max:50'],
            'complexion' => ['nullable', 'string', 'max:50'],
            'physical_status' => ['nullable', 'string', 'max:50'],
            'marital_status' => ['nullable', 'in:never_married,divorced,widowed,awaiting_divorce'],
            'num_children' => ['nullable', 'integer', 'min:0'],
            'about_me' => ['nullable', 'string', 'max:1000'],
            'religion_id' => ['nullable', 'exists:religions,id'],
            'caste_id' => ['nullable', 'exists:castes,id'],
            'subcaste_id' => ['nullable', 'exists:subcastes,id'],
            'mother_tongue_id' => ['nullable', 'exists:mother_tongues,id'],
            'gothra' => ['nullable', 'string', 'max:100'],
            'manglik' => ['nullable', 'boolean'],
            'horoscope' => ['nullable', 'string', 'max:100'],
            'star' => ['nullable', 'string', 'max:100'],
            'raasi' => ['nullable', 'string', 'max:100'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'citizenship' => ['nullable', 'string', 'max:100'],
            'residing_country' => ['nullable', 'string', 'max:100'],
            'education_id' => ['nullable', 'exists:educations,id'],
            'education_detail' => ['nullable', 'string', 'max:255'],
            'occupation_id' => ['nullable', 'exists:occupations,id'],
            'occupation_detail' => ['nullable', 'string', 'max:255'],
            'employer_name' => ['nullable', 'string', 'max:255'],
            'annual_income' => ['nullable', 'string', 'max:100'],
            'diet' => ['nullable', 'in:vegetarian,non_vegetarian,eggetarian,vegan'],
            'smoke' => ['nullable', 'in:no,occasionally,yes'],
            'drink' => ['nullable', 'in:no,occasionally,yes'],
            'family_type' => ['nullable', 'string', 'max:50'],
            'family_status' => ['nullable', 'string', 'max:50'],
            'family_values' => ['nullable', 'string', 'max:50'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'num_brothers' => ['nullable', 'integer', 'min:0'],
            'num_sisters' => ['nullable', 'integer', 'min:0'],
            'brothers_married' => ['nullable', 'integer', 'min:0'],
            'sisters_married' => ['nullable', 'integer', 'min:0'],
            'family_location' => ['nullable', 'string', 'max:255'],
            'about_family' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
















