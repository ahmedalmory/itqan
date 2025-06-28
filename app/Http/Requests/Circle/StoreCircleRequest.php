<?php

namespace App\Http\Requests\Circle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCircleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admins can create circles
        return Auth::user() && (Auth::user()->isSuperAdmin() || Auth::user()->isDepartmentAdmin());
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
            'description' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'max_students' => ['nullable', 'integer', 'min:1'],
            'whatsapp_group' => ['nullable', 'string', 'max:255'],
            'telegram_group' => ['nullable', 'string', 'max:255'],
            'age_from' => ['required', 'integer', 'min:5'],
            'age_to' => ['required', 'integer', 'max:100', 'gte:age_from'],
            'circle_time' => ['nullable', 'string', 'in:after_fajr,after_dhuhr,after_asr,after_maghrib,after_isha'],
            'is_active' => ['sometimes', 'boolean'],
            'location' => ['nullable', 'string', 'max:255'],
            'meeting_time' => ['nullable', 'string', 'max:255'],
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
            'name.required' => 'The circle name is required',
            'department_id.required' => 'Please select a department',
            'department_id.exists' => 'The selected department does not exist',
            'teacher_id.exists' => 'The selected teacher does not exist',
            'supervisor_id.exists' => 'The selected supervisor does not exist',
            'age_from.required' => 'Please enter the minimum age',
            'age_to.required' => 'Please enter the maximum age',
            'age_to.gte' => 'The maximum age must be greater than or equal to the minimum age',
        ];
    }
} 