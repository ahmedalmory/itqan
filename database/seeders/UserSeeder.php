<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Country;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some countries for realistic data
        $countries = Country::pluck('id')->toArray();

        $preferredTimes = [
            'after_fajr', 'after_dhuhr', 'after_asr', 'after_maghrib', 'after_isha'
        ];
        $randomPreferredTime = function () use ($preferredTimes) {
            return $preferredTimes[array_rand($preferredTimes)];
        };

        // Create super admin
        User::create([
            'name' => 'أحمد المدير',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'phone' => '9665xxxxxxxx',
            'age' => 40,
            'gender' => 'male',
            'role' => 'super_admin',
            'country_id' => $countries[array_rand($countries)],
            'is_active' => true,
        ]);

        // Create department admins
        $departmentAdmins = [
            [
                'name' => 'محمد عبدالله',
                'email' => 'dept_admin1@example.com',
                'phone' => '9665xxxxxxx1',
                'gender' => 'male',
            ],
            [
                'name' => 'خالد العمري',
                'email' => 'dept_admin2@example.com',
                'phone' => '9665xxxxxxx2',
                'gender' => 'male',
            ],
            [
                'name' => 'فاطمة الزهراء',
                'email' => 'dept_admin3@example.com',
                'phone' => '9665xxxxxxx3',
                'gender' => 'female',
            ],
        ];

        foreach ($departmentAdmins as $admin) {
            User::create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => bcrypt('password'),
                'phone' => $admin['phone'],
                'age' => rand(30, 50),
                'gender' => $admin['gender'],
                'role' => 'department_admin',
                'country_id' => $countries[array_rand($countries)],
                'is_active' => true,
            ]);
        }

        // Create teachers
        $teachers = [
            [
                'name' => 'عبدالرحمن الشمري',
                'email' => 'teacher1@example.com',
                'phone' => '9665xxxxxxx4',
                'gender' => 'male',
            ],
            [
                'name' => 'عمر السلمي',
                'email' => 'teacher2@example.com',
                'phone' => '9665xxxxxxx5',
                'gender' => 'male',
            ],
            [
                'name' => 'سارة الأحمد',
                'email' => 'teacher3@example.com',
                'phone' => '9665xxxxxxx6',
                'gender' => 'female',
            ],
            [
                'name' => 'نورة القحطاني',
                'email' => 'teacher4@example.com',
                'phone' => '9665xxxxxxx7',
                'gender' => 'female',
            ],
            [
                'name' => 'بدر العتيبي',
                'email' => 'teacher5@example.com',
                'phone' => '9665xxxxxxx8',
                'gender' => 'male',
            ],
        ];

        foreach ($teachers as $teacher) {
            User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => bcrypt('password'),
                'phone' => $teacher['phone'],
                'age' => rand(25, 55),
                'gender' => $teacher['gender'],
                'role' => 'teacher',
                'country_id' => $countries[array_rand($countries)],
                'is_active' => true,
                'preferred_time' => $randomPreferredTime(),
            ]);
        }

        // Create supervisors
        $supervisors = [
            [
                'name' => 'يوسف الدوسري',
                'email' => 'supervisor1@example.com',
                'phone' => '9665xxxxxxx9',
                'gender' => 'male',
            ],
            [
                'name' => 'عائشة المالكي',
                'email' => 'supervisor2@example.com',
                'phone' => '9665xxxxxx10',
                'gender' => 'female',
            ],
        ];

        foreach ($supervisors as $supervisor) {
            User::create([
                'name' => $supervisor['name'],
                'email' => $supervisor['email'],
                'password' => bcrypt('password'),
                'phone' => $supervisor['phone'],
                'age' => rand(30, 50),
                'gender' => $supervisor['gender'],
                'role' => 'supervisor',
                'country_id' => $countries[array_rand($countries)],
                'is_active' => true,
                // preferred_time is nullable for supervisor
            ]);
        }

        // Create students (male)
        $maleStudents = [
            [
                'name' => 'عبدالله المطيري',
                'email' => 'mstudent1@example.com',
                'phone' => '9665xxxxxx11',
            ],
            [
                'name' => 'سعد الغامدي',
                'email' => 'mstudent2@example.com',
                'phone' => '9665xxxxxx12',
            ],
            [
                'name' => 'فهد الحارثي',
                'email' => 'mstudent3@example.com',
                'phone' => '9665xxxxxx13',
            ],
            [
                'name' => 'محمد العنزي',
                'email' => 'mstudent4@example.com',
                'phone' => '9665xxxxxx14',
            ],
            [
                'name' => 'طارق الشهري',
                'email' => 'mstudent5@example.com',
                'phone' => '9665xxxxxx15',
            ],
            [
                'name' => 'أحمد الزهراني',
                'email' => 'mstudent6@example.com',
                'phone' => '9665xxxxxx16',
            ],
            [
                'name' => 'ناصر البلوي',
                'email' => 'mstudent7@example.com',
                'phone' => '9665xxxxxx17',
            ],
            [
                'name' => 'عبدالعزيز القرني',
                'email' => 'mstudent8@example.com',
                'phone' => '9665xxxxxx18',
            ],
        ];

        foreach ($maleStudents as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => bcrypt('password'),
                'phone' => $student['phone'],
                'age' => rand(12, 25),
                'gender' => 'male',
                'role' => 'student',
                'country_id' => $countries[array_rand($countries)],
                'is_active' => true,
                'preferred_time' => $randomPreferredTime(),
            ]);
        }

        // Create students (female)
        $femaleStudents = [
            [
                'name' => 'منى الحربي',
                'email' => 'fstudent1@example.com',
                'phone' => '9665xxxxxx19',
            ],
            [
                'name' => 'هدى الشمراني',
                'email' => 'fstudent2@example.com',
                'phone' => '9665xxxxxx20',
            ],
            [
                'name' => 'رغد السبيعي',
                'email' => 'fstudent3@example.com',
                'phone' => '9665xxxxxx21',
            ],
            [
                'name' => 'نوف العتيبي',
                'email' => 'fstudent4@example.com',
                'phone' => '9665xxxxxx22',
            ],
            [
                'name' => 'ريم الدوسري',
                'email' => 'fstudent5@example.com',
                'phone' => '9665xxxxxx23',
            ],
            [
                'name' => 'غادة القحطاني',
                'email' => 'fstudent6@example.com',
                'phone' => '9665xxxxxx24',
            ],
            [
                'name' => 'نورة العنزي',
                'email' => 'fstudent7@example.com',
                'phone' => '9665xxxxxx25',
            ],
        ];

        foreach ($femaleStudents as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => bcrypt('password'),
                'phone' => $student['phone'],
                'age' => rand(12, 25),
                'gender' => 'female',
                'role' => 'student',
                'country_id' => $countries[array_rand($countries)],
                'is_active' => true,
                'preferred_time' => $randomPreferredTime(),
            ]);
        }
    }
}
