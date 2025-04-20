<?php

namespace Database\Seeders;

use App\Models\CircleStudent;
use App\Models\StudyCircle;
use App\Models\User;
use Illuminate\Database\Seeder;

class CircleStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get male students, female students, male circles, and female circles
        $maleStudents = User::where('role', 'student')
            ->where('gender', 'male')
            ->pluck('id')
            ->toArray();
            
        $femaleStudents = User::where('role', 'student')
            ->where('gender', 'female')
            ->pluck('id')
            ->toArray();
            
        $maleCircles = StudyCircle::whereHas('department', function ($query) {
                $query->where('student_gender', 'male')->orWhere('student_gender', 'both');
            })->pluck('id')->toArray();
            
        $femaleCircles = StudyCircle::whereHas('department', function ($query) {
                $query->where('student_gender', 'female')->orWhere('student_gender', 'both');
            })->pluck('id')->toArray();
        
        // Assign male students to male circles
        foreach ($maleStudents as $studentId) {
            // Assign to 1-2 circles
            $numCircles = rand(1, 2);
            $assignedCircles = [];
            
            for ($i = 0; $i < $numCircles && !empty($maleCircles); $i++) {
                $randomCircleIndex = array_rand($maleCircles);
                $circleId = $maleCircles[$randomCircleIndex];
                
                // Check if not already assigned
                if (!in_array($circleId, $assignedCircles)) {
                    CircleStudent::create([
                        'student_id' => $studentId,
                        'circle_id' => $circleId,
                    ]);
                    $assignedCircles[] = $circleId;
                }
            }
        }
        
        // Assign female students to female circles
        foreach ($femaleStudents as $studentId) {
            // Assign to 1-2 circles
            $numCircles = rand(1, 2);
            $assignedCircles = [];
            
            for ($i = 0; $i < $numCircles && !empty($femaleCircles); $i++) {
                $randomCircleIndex = array_rand($femaleCircles);
                $circleId = $femaleCircles[$randomCircleIndex];
                
                // Check if not already assigned
                if (!in_array($circleId, $assignedCircles)) {
                    CircleStudent::create([
                        'student_id' => $studentId,
                        'circle_id' => $circleId,
                    ]);
                    $assignedCircles[] = $circleId;
                }
            }
        }
    }
} 