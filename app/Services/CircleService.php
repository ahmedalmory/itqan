<?php

namespace App\Services;

use App\Models\CircleStudent;
use App\Models\StudyCircle;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CircleService
{
    /**
     * Create a new study circle.
     *
     * @param array $data
     * @return \App\Models\StudyCircle
     */
    public function createCircle(array $data): StudyCircle
    {
        return StudyCircle::create($data);
    }
    
    /**
     * Update an existing study circle.
     *
     * @param \App\Models\StudyCircle $circle
     * @param array $data
     * @return \App\Models\StudyCircle
     */
    public function updateCircle(StudyCircle $circle, array $data): StudyCircle
    {
        $circle->update($data);
        return $circle;
    }
    
    /**
     * Enroll a student in a circle.
     *
     * @param int $circleId
     * @param int $studentId
     * @return \App\Models\CircleStudent
     * 
     * @throws \Exception
     */
    public function enrollStudent(int $circleId, int $studentId): CircleStudent
    {
        // Get the circle and student
        $circle = StudyCircle::findOrFail($circleId);
        $student = User::findOrFail($studentId);
        
        // Check if the student is already enrolled
        if ($circle->students()->where('users.id', $studentId)->exists()) {
            throw new \Exception('Student is already enrolled in this circle.');
        }
        
        // Check circle capacity
        if ($circle->max_students && $circle->students()->count() >= $circle->max_students) {
            throw new \Exception('Circle has reached maximum capacity.');
        }
        
        // Check student age fits circle requirements
        if ($student->age < $circle->age_from || $student->age > $circle->age_to) {
            throw new \Exception('Student age does not fit circle requirements.');
        }
        
        // Check student gender matches department gender
        $department = $circle->department;
        if ($student->gender !== $department->student_gender) {
            throw new \Exception('Student gender does not match department requirements.');
        }
        
        try {
            // Begin transaction
            DB::beginTransaction();
            
            // Create the enrollment
            $enrollment = CircleStudent::create([
                'circle_id' => $circleId,
                'student_id' => $studentId,
            ]);
            
            // Create initial student points record
            $student->studentPoints()->create([
                'circle_id' => $circleId,
                'total_points' => 0,
            ]);
            
            // Commit transaction
            DB::commit();
            
            return $enrollment;
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            Log::error('Failed to enroll student: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Remove a student from a circle.
     *
     * @param int $circleId
     * @param int $studentId
     * @return bool
     */
    public function removeStudent(int $circleId, int $studentId): bool
    {
        return CircleStudent::where('circle_id', $circleId)
            ->where('student_id', $studentId)
            ->delete();
    }
    
    /**
     * Get all circles suitable for a student.
     *
     * @param \App\Models\User $student
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCirclesForStudent(User $student)
    {
        return StudyCircle::whereHas('department', function ($query) use ($student) {
                $query->where('student_gender', $student->gender);
            })
            ->where('age_from', '<=', $student->age)
            ->where('age_to', '>=', $student->age)
            ->get();
    }
    
    /**
     * Check if a student can join a circle.
     *
     * @param \App\Models\User $student
     * @param \App\Models\StudyCircle $circle
     * @return bool
     */
    public function canStudentJoinCircle(User $student, StudyCircle $circle): bool
    {
        // Check if student already enrolled
        if ($circle->students()->where('users.id', $student->id)->exists()) {
            return false;
        }
        
        // Check circle capacity
        if ($circle->max_students && $circle->students()->count() >= $circle->max_students) {
            return false;
        }
        
        // Check student age
        if ($student->age < $circle->age_from || $student->age > $circle->age_to) {
            return false;
        }
        
        // Check student gender matches department gender
        $department = $circle->department;
        if ($student->gender !== $department->student_gender) {
            return false;
        }
        
        return true;
    }
} 