<?php

namespace Database\Seeders;

use App\Models\StudyCircle;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;

class StudyCircleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teachers, supervisors and departments
        $maleTeachers = User::where('role', 'teacher')
            ->where('gender', 'male')
            ->pluck('id')
            ->toArray();

        $femaleTeachers = User::where('role', 'teacher')
            ->where('gender', 'female')
            ->pluck('id')
            ->toArray();

        $maleSupervisors = User::where('role', 'supervisor')
            ->where('gender', 'male')
            ->pluck('id')
            ->toArray();
            
        $femaleSupervisors = User::where('role', 'supervisor')
            ->where('gender', 'female')
            ->pluck('id')
            ->toArray();

        $maleDepartment = Department::where('student_gender', 'male')->first()->id;
        $femaleDepartment = Department::where('student_gender', 'female')->first()->id;
        $bothDepartment = Department::where('student_gender', 'mixed')->first()->id;

        // Use enum values for circle_time as per migration
        $circleTimes = [
            'after_fajr', 'after_dhuhr', 'after_asr', 'after_maghrib', 'after_isha'
        ];

        // Create male study circles
        $maleCircles = [
            [
                'name' => 'حلقة الفرقان',
                'description' => 'حلقة تحفيظ للطلاب الذكور المبتدئين',
                'department_id' => $maleDepartment,
                'max_students' => 10,
                'whatsapp_group' => 'https://chat.whatsapp.com/example1',
                'telegram_group' => 'https://t.me/example1',
                'age_from' => 10,
                'age_to' => 15,
                'circle_time' => 'after_maghrib',
            ],
            [
                'name' => 'حلقة النور',
                'description' => 'حلقة تحفيظ للطلاب الذكور المتوسطين',
                'department_id' => $maleDepartment,
                'max_students' => 8,
                'whatsapp_group' => 'https://chat.whatsapp.com/example2',
                'telegram_group' => 'https://t.me/example2',
                'age_from' => 16,
                'age_to' => 20,
                'circle_time' => 'after_isha',
            ],
            [
                'name' => 'حلقة التجويد للرجال',
                'description' => 'حلقة متخصصة في أحكام التجويد للطلاب البالغين',
                'department_id' => $bothDepartment,
                'max_students' => 12,
                'whatsapp_group' => 'https://chat.whatsapp.com/example3',
                'telegram_group' => 'https://t.me/example3',
                'age_from' => 21,
                'age_to' => 60,
                'circle_time' => 'after_dhuhr',
            ],
        ];

        foreach ($maleCircles as $circle) {
            StudyCircle::create([
                'name' => $circle['name'],
                'description' => $circle['description'],
                'department_id' => $circle['department_id'],
                'teacher_id' => $maleTeachers[array_rand($maleTeachers)],
                'supervisor_id' => $maleSupervisors[array_rand($maleSupervisors)],
                'max_students' => $circle['max_students'],
                'whatsapp_group' => $circle['whatsapp_group'],
                'telegram_group' => $circle['telegram_group'],
                'age_from' => $circle['age_from'],
                'age_to' => $circle['age_to'],
                'circle_time' => $circle['circle_time'],
            ]);
        }

        // Create female study circles
        $femaleCircles = [
            [
                'name' => 'حلقة الزهراء',
                'description' => 'حلقة تحفيظ للطالبات المبتدئات',
                'department_id' => $femaleDepartment,
                'max_students' => 10,
                'whatsapp_group' => 'https://chat.whatsapp.com/example4',
                'telegram_group' => 'https://t.me/example4',
                'age_from' => 10,
                'age_to' => 15,
                'circle_time' => 'after_asr',
            ],
            [
                'name' => 'حلقة الهدى',
                'description' => 'حلقة تحفيظ للطالبات المتوسطات',
                'department_id' => $femaleDepartment,
                'max_students' => 8,
                'whatsapp_group' => 'https://chat.whatsapp.com/example5',
                'telegram_group' => 'https://t.me/example5',
                'age_from' => 16,
                'age_to' => 20,
                'circle_time' => 'after_maghrib',
            ],
            [
                'name' => 'حلقة التجويد للنساء',
                'description' => 'حلقة متخصصة في أحكام التجويد للطالبات البالغات',
                'department_id' => $bothDepartment,
                'max_students' => 12,
                'whatsapp_group' => 'https://chat.whatsapp.com/example6',
                'telegram_group' => 'https://t.me/example6',
                'age_from' => 21,
                'age_to' => 60,
                'circle_time' => 'after_isha',
            ],
        ];

        foreach ($femaleCircles as $circle) {
            StudyCircle::create([
                'name' => $circle['name'],
                'description' => $circle['description'],
                'department_id' => $circle['department_id'],
                'teacher_id' => $femaleTeachers[array_rand($femaleTeachers)],
                'supervisor_id' => $femaleSupervisors[array_rand($femaleSupervisors)],
                'max_students' => $circle['max_students'],
                'whatsapp_group' => $circle['whatsapp_group'],
                'telegram_group' => $circle['telegram_group'],
                'age_from' => $circle['age_from'],
                'age_to' => $circle['age_to'],
                'circle_time' => $circle['circle_time'],
            ]);
        }

        // Create advanced circles
        $advancedCircles = [
            [
                'name' => 'حلقة الإجازة',
                'description' => 'حلقة متقدمة للطلاب الراغبين في الحصول على إجازة في القرآن الكريم',
                'department_id' => $bothDepartment,
                'max_students' => 5,
                'whatsapp_group' => 'https://chat.whatsapp.com/example7',
                'telegram_group' => 'https://t.me/example7',
                'age_from' => 18,
                'age_to' => 60,
                'circle_time' => 'after_fajr',
            ],
        ];

        foreach ($advancedCircles as $circle) {
            StudyCircle::create([
                'name' => $circle['name'],
                'description' => $circle['description'],
                'department_id' => $circle['department_id'],
                'teacher_id' => $maleTeachers[array_rand($maleTeachers)],
                'supervisor_id' => $maleSupervisors[array_rand($maleSupervisors)],
                'max_students' => $circle['max_students'],
                'whatsapp_group' => $circle['whatsapp_group'],
                'telegram_group' => $circle['telegram_group'],
                'age_from' => $circle['age_from'],
                'age_to' => $circle['age_to'],
                'circle_time' => $circle['circle_time'],
            ]);
        }
    }
}