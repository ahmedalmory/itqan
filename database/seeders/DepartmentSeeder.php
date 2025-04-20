<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'قسم تحفيظ البنين',
                'description' => 'قسم مخصص لتحفيظ القرآن الكريم للذكور بمختلف الأعمار',
                'student_gender' => 'male',
                'work_friday' => true,
                'work_saturday' => true,
                'work_sunday' => true,
                'work_monday' => true,
                'work_tuesday' => true,
                'work_wednesday' => true,
                'work_thursday' => true,
                'monthly_fees' => 50.00,
                'quarterly_fees' => 130.00,
                'biannual_fees' => 240.00,
                'annual_fees' => 450.00,
                'restrict_countries' => false,
                'registration_open' => true,
            ],
            [
                'name' => 'قسم تحفيظ البنات',
                'description' => 'قسم مخصص لتحفيظ القرآن الكريم للإناث بمختلف الأعمار',
                'student_gender' => 'female',
                'work_friday' => false,
                'work_saturday' => true,
                'work_sunday' => true,
                'work_monday' => true,
                'work_tuesday' => true,
                'work_wednesday' => true,
                'work_thursday' => true,
                'monthly_fees' => 50.00,
                'quarterly_fees' => 130.00,
                'biannual_fees' => 240.00,
                'annual_fees' => 450.00,
                'restrict_countries' => false,
                'registration_open' => true,
            ],
            [
                'name' => 'قسم الإجازة',
                'description' => 'قسم مخصص للطلاب المتقدمين للحصول على إجازة في القرآن الكريم',
                'student_gender' => 'mixed',
                'work_friday' => false,
                'work_saturday' => false,
                'work_sunday' => true,
                'work_monday' => true,
                'work_tuesday' => true,
                'work_wednesday' => true,
                'work_thursday' => false,
                'monthly_fees' => 80.00,
                'quarterly_fees' => 210.00,
                'biannual_fees' => 380.00,
                'annual_fees' => 700.00,
                'restrict_countries' => false,
                'registration_open' => true,
            ],
            [
                'name' => 'قسم التجويد',
                'description' => 'قسم متخصص في تعليم أحكام تجويد القرآن الكريم',
                'student_gender' => 'mixed',
                'work_friday' => false,
                'work_saturday' => true,
                'work_sunday' => false,
                'work_monday' => true,
                'work_tuesday' => false,
                'work_wednesday' => true,
                'work_thursday' => false,
                'monthly_fees' => 45.00,
                'quarterly_fees' => 120.00,
                'biannual_fees' => 220.00,
                'annual_fees' => 400.00,
                'restrict_countries' => false,
                'registration_open' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
} 