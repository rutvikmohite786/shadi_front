<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'type' => ['nullable', 'in:profile,gallery'],
        ];
    }

    public function messages(): array
    {
        return [
            'photo.max' => 'Photo size must not exceed 5MB.',
            'photo.mimes' => 'Photo must be a JPEG, PNG, JPG or WebP image.',
        ];
    }
}

















