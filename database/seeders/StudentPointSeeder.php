<?php

namespace Database\Seeders;

use App\Models\StudentPoint;
use App\Models\CircleStudent;
use App\Models\PointsHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the super admin user
        $superAdmin = User::where('role', 'super_admin')->first();
        if (!$superAdmin) {
            $this->command->error('Super admin user not found. Please run UserSeeder first.');
            return;
        }

        // Get all students in circles
        $circleStudents = CircleStudent::all();

        // Define possible action types as per migration
        $actionTypes = ['add', 'subtract', 'reset'];

        foreach ($circleStudents as $circleStudent) {
            // Assign a random total points value for the student in the circle
            $totalPoints = rand(50, 500);

            // Create or update student points
            StudentPoint::updateOrCreate(
                [
                    'student_id' => $circleStudent->student_id,
                    'circle_id' => $circleStudent->circle_id,
                ],
                [
                    'total_points' => $totalPoints,
                    'last_updated' => now(),
                ]
            );

            // Create points history (3-7 entries per student)
            $historyEntries = rand(3, 7);

            for ($i = 0; $i < $historyEntries; $i++) {
                // Randomly select action type as per migration
                $actionType = $actionTypes[array_rand($actionTypes)];

                // Determine points value based on action type
                if ($actionType === 'reset') {
                    $pointValue = 0;
                } elseif ($actionType === 'subtract') {
                    $pointValue = -1 * rand(10, 50);
                } else { // 'add'
                    $pointValue = rand(10, 50);
                }

                // Generate notes for the history entry
                $notes = $this->generatePointNotes($actionType, abs($pointValue));

                // Use consistent created_at/updated_at for realism
                $daysAgo = rand(1, 30);
                $timestamp = now()->subDays($daysAgo);

                PointsHistory::create([
                    'student_id' => $circleStudent->student_id,
                    'circle_id' => $circleStudent->circle_id,
                    'points' => $pointValue,
                    'action_type' => $actionType,
                    'notes' => $notes,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                    'created_by' => $superAdmin->id,
                ]);
            }
        }
    }

    /**
     * Generate a point notes string based on action type and value.
     */
    private function generatePointNotes($actionType, $value)
    {
        switch ($actionType) {
            case 'add':
                $notesArr = [
                    'إضافة نقاط للحفظ الممتاز',
                    'إضافة نقاط للمواظبة على الحضور',
                    'إضافة نقاط للمشاركة في المسابقة',
                    'إضافة نقاط للسلوك الحسن',
                    'إضافة نقاط للمشاركة في الأنشطة',
                ];
                $note = $notesArr[array_rand($notesArr)] . ' +' . $value . ' نقطة';
                break;
            case 'subtract':
                $notesArr = [
                    'خصم نقاط بسبب الغياب',
                    'خصم نقاط بسبب التأخر',
                    'خصم نقاط بسبب مخالفة التعليمات',
                    'خصم نقاط بسبب ضعف المشاركة',
                ];
                $note = $notesArr[array_rand($notesArr)] . ' -' . $value . ' نقطة';
                break;
            case 'reset':
                $note = 'إعادة تعيين النقاط إلى الصفر';
                break;
            default:
                $note = 'تعديل نقاط الطالب';
        }
        return $note;
    }
}