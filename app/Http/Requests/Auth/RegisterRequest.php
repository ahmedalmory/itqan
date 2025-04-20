<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'age' => ['required', 'integer', 'min:5', 'max:100'],
            'gender' => ['required', 'string', 'in:male,female'],
            'role' => ['sometimes', 'string', 'in:student'],  // Only allow student role for public registration
            'preferred_time' => ['sometimes', 'string', 'in:after_fajr,after_dhuhr,after_asr,after_maghrib,after_isha'],
            'country_id' => ['sometimes', 'exists:countries,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already registered',
            'password.required' => 'Please enter a password',
            'password.confirmed' => 'The passwords do not match',
            'phone.required' => 'Please enter your phone number',
            'age.required' => 'Please enter your age',
            'age.integer' => 'Age must be a number',
            'age.min' => 'Age must be at least 5 years',
            'age.max' => 'Age must be at most 100 years',
            'gender.required' => 'Please select your gender',
            'gender.in' => 'Please select a valid gender',
        ];
    }
}