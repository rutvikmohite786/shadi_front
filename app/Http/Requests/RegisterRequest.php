<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[6-9][0-9]{9}$/',
                'unique:users',
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['required', 'in:male,female'],
            'dob' => ['required', 'date', 'before:-18 years'],
        ];
    }

    public function messages(): array
    {
        return [
            'dob.before' => 'You must be at least 18 years old to register.',
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6,7,8, or 9.',
        ];
    }
}

















