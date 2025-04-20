<?php

namespace App\Http\Requests\Circle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCircleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Admin or teacher or supervisor of the circle
        $circle = $this->route('circle');
        $user = Auth::user();
        
        return $user && (
            $user->isSuperAdmin() ||
            $user->isDepartmentAdmin() ||
            ($user->isTeacher() && $circle->teacher_id === $user->id) ||
            ($user->isSupervisor() && $circle->supervisor_id === $user->id)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // User with admin role can update all fields
        if (Auth::user()->isSuperAdmin() || Auth::user()->isDepartmentAdmin()) {
            return [
                'name' => ['sometimes', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'department_id' => ['sometimes', 'exists:departments,id'],
                'teacher_id' => ['nullable', 'exists:users,id'],
                'supervisor_id' => ['nullable', 'exists:users,id'],
                'max_students' => ['nullable', 'integer', 'min:1'],
                'whatsapp_group' => ['nullable', 'string', 'max:255'],
                'telegram_group' => ['nullable', 'string', 'max:255'],
                'age_from' => ['sometimes', 'integer', 'min:5'],
                'age_to' => ['sometimes', 'integer', 'max:100', 'gte:age_from'],
                'circle_time' => ['nullable', 'string', 'in:after_fajr,after_dhuhr,after_asr,after_maghrib,after_isha'],
            ];
        }
        
        // Non-admin users can only update limited fields
        return [
            'description' => ['nullable', 'string'],
            'whatsapp_group' => ['nullable', 'string', 'max:255'],
            'telegram_group' => ['nullable', 'string', 'max:255'],
            'circle_time' => ['nullable', 'string', 'in:after_fajr,after_dhuhr,after_asr,after_maghrib,after_isha'],
        ];
    }
} 