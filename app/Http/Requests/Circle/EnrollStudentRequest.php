<?php

namespace App\Http\Requests\Circle;

use App\Models\StudyCircle;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EnrollStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Admin or student enrolling themselves
        $user = Auth::user();
        
        // For admin or teacher trying to enroll a student
        if ($user->isSuperAdmin() || $user->isDepartmentAdmin() || $user->isTeacher()) {
            return true;
        }
        
        // For students enrolling themselves
        if ($user->isStudent() && (!$this->has('student_id') || $this->input('student_id') == $user->id)) {
            return true;
        }
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:users,id'],
        ];
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // If no student_id provided and user is a student, use current user
        if ((!$this->has('student_id') || empty($this->input('student_id'))) && Auth::user()->isStudent()) {
            $this->merge([
                'student_id' => Auth::id(),
            ]);
        }
    }
    
    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if the circle has reached max capacity
            $circleId = $this->input('circle_id');
            $circle = StudyCircle::find($circleId);
            
            if ($circle && $circle->max_students) {
                $currentStudentCount = $circle->students()->count();
                if ($currentStudentCount >= $circle->max_students) {
                    $validator->errors()->add('circle_id', 'This circle has reached its maximum capacity.');
                }
            }
            
            // Check if student age fits the circle requirements
            $studentId = $this->input('student_id');
            $student = User::find($studentId);
            
            if ($circle && $student) {
                if ($student->age < $circle->age_from || $student->age > $circle->age_to) {
                    $validator->errors()->add('student_id', 'Student age does not fit the circle age requirements.');
                }
            }
            
            // Check if the student is already enrolled in this circle
            if ($circle && $student && $circle->students()->where('users.id', $student->id)->exists()) {
                $validator->errors()->add('student_id', 'Student is already enrolled in this circle.');
            }
        });
    }
} 