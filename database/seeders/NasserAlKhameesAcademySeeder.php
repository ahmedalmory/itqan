<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Country;
use App\Models\Department;
use App\Models\StudyCircle;
use App\Models\CircleStudent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NasserAlKhameesAcademySeeder extends Seeder
{
    /**
     * Run the database seeds for Nasser Al-Khamees Academy (مسجد ناصر بن علي الخميس)
     */
    public function run(): void
    {
        // Get countries
        $saudiArabia = Country::where('name', 'Saudi Arabia')->first();
        $egypt = Country::where('name', 'Egypt')->first();
        $syria = Country::where('name', 'Syria')->first();
        $bahrain = Country::where('name', 'Bahrain')->first();
        $jordan = Country::where('name', 'Jordan')->first();
        $oman = Country::where('name', 'Oman')->first();
        $yemen = Country::where('name', 'Yemen')->first();
        $estonia = Country::where('name', 'Estonia')->first();

        // Create super admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@nasser-academy.com',
            'password' => Hash::make('admin123'),
            'phone' => '966500000000',
            'age' => 35,
            'gender' => 'male',
            'role' => 'super_admin',
            'country_id' => $saudiArabia->id,
            'is_active' => true,
        ]);

        // Create departments
        $department = Department::create([
            'name' => 'Z11 مجمع إتقان بمسجد علي بن ناصر الخميس',
            'description' => 'قسم مجمع إتقان الرقمي للقرآن الكريم - مسجد ناصر بن علي الخميس',
            'student_gender' => 'male',
            'work_friday' => true,
            'work_saturday' => true,
            'work_sunday' => true,
            'work_monday' => true,
            'work_tuesday' => true,
            'work_wednesday' => true,
            'work_thursday' => true,
            'monthly_fees' => 0,
            'quarterly_fees' => 0,
            'biannual_fees' => 0,
            'annual_fees' => 0,
            'restrict_countries' => false,
            'registration_open' => true,
        ]);

        // Create teachers first
        $teachers = [
            ['name' => 'انس سليمان  عيسى', 'email' => 'anas.teacher@gmail.com', 'phone' => '966509763628', 'country' => 'Saudi Arabia'],
            ['name' => 'محمد أنور عبد الباقي السيد', 'email' => 'anwar.teacher@gmail.com', 'phone' => '20545139544', 'country' => 'Egypt'],
            ['name' => 'حذيفة بن احمد المعيوف', 'email' => 'huthaifa.teacher@gmail.com', 'phone' => '966594378842', 'country' => 'Saudi Arabia'],
            ['name' => 'عصام سعد أحمد سعد', 'email' => 'essam.teacher@gmail.com', 'phone' => '966567300247', 'country' => 'Saudi Arabia'],
            ['name' => 'علي بن سليمان', 'email' => 'ali.teacher@gmail.com', 'phone' => '966548984174', 'country' => 'Saudi Arabia'],
            ['name' => 'محمد الزيني', 'email' => 'zini.teacher@gmail.com', 'phone' => '966539404929', 'country' => 'Saudi Arabia'],
            ['name' => 'أحمد محمد موسى', 'email' => 'ahmad.teacher@gmail.com', 'phone' => '963551083678', 'country' => 'Syria'],
            ['name' => 'محمد ابو زاهر موسى', 'email' => 'abuzaher.teacher@gmail.com', 'phone' => '963504499864', 'country' => 'Syria'],
            ['name' => 'أسامة بن سعود التميمي', 'email' => 'osama.teacher@gmail.com', 'phone' => '966540440554', 'country' => 'Saudi Arabia'],
        ];

        $teacherModels = [];
        foreach ($teachers as $teacherData) {
            $teacher = User::create([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'password' => Hash::make('password123'),
                'phone' => $teacherData['phone'],
                'role' => 'teacher',
                'country_id' => Country::where('name', $teacherData['country'])->first()->id,
                'is_active' => true,
            ]);
            $teacherModels[$teacherData['name']] = $teacher;
        }

        // Create circles with their assigned teachers
        $circles = [];
        
        $circles['حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'] = StudyCircle::create([
            'name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['انس سليمان  عيسى']->id,
            'age_from' => 5,
            'age_to' => 8,
            'circle_time' => 'after_asr',
            'is_active' => true,
        ]);

        $circles['حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'] = StudyCircle::create([
            'name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-', 
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['محمد أنور عبد الباقي السيد']->id,
            'age_from' => 5,
            'age_to' => 8,
            'circle_time' => 'after_asr',
            'is_active' => true,
        ]);

        $circles['حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'] = StudyCircle::create([
            'name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['حذيفة بن احمد المعيوف']->id,
            'age_from' => 9,
            'age_to' => 12,
            'circle_time' => 'after_asr',
            'is_active' => true,
        ]);

        $circles['حلقة المربي عصام سعد (9 - 12 سنة)-فترة العصر-'] = StudyCircle::create([
            'name' => 'حلقة المربي عصام سعد (9 - 12 سنة)-فترة العصر-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['عصام سعد أحمد سعد']->id,
            'age_from' => 9,
            'age_to' => 12,
            'circle_time' => 'after_asr',
            'is_active' => true,
        ]);

        $circles['حلقة المربي علي سليمان( 9 - 12 سنة) -فترة العصر-'] = StudyCircle::create([
            'name' => 'حلقة المربي علي سليمان( 9 - 12 سنة) -فترة العصر-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['علي بن سليمان']->id,
            'age_from' => 9,
            'age_to' => 12,
            'circle_time' => 'after_asr',
            'is_active' => true,
        ]);

        $circles['حلقة المربي محمد الزيني (9 - 12 سنة)-فترة المغرب-'] = StudyCircle::create([
            'name' => 'حلقة المربي محمد الزيني (9 - 12 سنة)-فترة المغرب-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['محمد الزيني']->id,
            'age_from' => 9,
            'age_to' => 12,
            'circle_time' => 'after_maghrib',
            'is_active' => true,
        ]);

        $circles['حلقة المعلم أحمد محمد - سادس ابتدائي وأول متوسط -فترة المغرب-'] = StudyCircle::create([
            'name' => 'حلقة المعلم أحمد محمد - سادس ابتدائي وأول متوسط -فترة المغرب-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['أحمد محمد موسى']->id,
            'age_from' => 12,
            'age_to' => 15,
            'circle_time' => 'after_maghrib',
            'is_active' => true,
        ]);

        $circles['حلقة المعلم علي سليمان - المتوسطة الثانية -فترة المغرب-'] = StudyCircle::create([
            'name' => 'حلقة المعلم علي سليمان - المتوسطة الثانية -فترة المغرب-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['علي بن سليمان']->id,
            'age_from' => 14,
            'age_to' => 16,
            'circle_time' => 'after_maghrib',
            'is_active' => true,
        ]);

        $circles['حلقة المعلم محمد أبو زاهر - ثالث متوسط والثانوي -فترة المغرب-'] = StudyCircle::create([
            'name' => 'حلقة المعلم محمد أبو زاهر - ثالث متوسط والثانوي -فترة المغرب-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['محمد ابو زاهر موسى']->id,
            'age_from' => 16,
            'age_to' => 18,
            'circle_time' => 'after_maghrib',
            'is_active' => true,
        ]);

        $circles['حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'] = StudyCircle::create([
            'name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-',
            'department_id' => $department->id,
            'teacher_id' => $teacherModels['أسامة بن سعود التميمي']->id,
            'age_from' => 18,
            'age_to' => 60,
            'circle_time' => 'after_maghrib',
            'is_active' => true,
        ]);

        // Now create students with their actual data from the CSV
        $this->createStudentsFromCsvData($circles, $saudiArabia, $egypt, $syria, $bahrain, $jordan, $oman, $yemen, $estonia);
    }

    private function createStudentsFromCsvData($circles, $saudiArabia, $egypt, $syria, $bahrain, $jordan, $oman, $yemen, $estonia)
    {
        // Helper function to get country by name
        $getCountryByName = function($countryName) use ($saudiArabia, $egypt, $syria, $bahrain, $jordan, $oman, $yemen, $estonia) {
            switch ($countryName) {
                case 'Saudi Arabia':
                    return $saudiArabia;
                case 'Egypt':
                    return $egypt;
                case 'Syria':
                    return $syria;
                case 'Bahrain':
                    return $bahrain;
                case 'Jordan':
                    return $jordan;
                case 'Oman':
                    return $oman;
                case 'Yemen':
                    return $yemen;
                case 'Estonia':
                    return $estonia;
                default:
                    return $saudiArabia; // Default to Saudi Arabia
            }
        };

        // Include all students from the CSV data - organized by circles
        $students = [
            // حلقة أشبال القرآن (المربي أنس سليمان) - 23 students
            ['name' => 'احمد سنادة بابكر', 'email' => 'ahmed2025@gmail.com', 'age' => 8, 'phone' => '966560000006', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'حمزة مختار مأمون', 'email' => 'hamza2025@gmail.com', 'age' => 8, 'phone' => '9665600000013', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'خلف فالح الدوسري', 'email' => 'khalaf2025@gmail.com', 'age' => 8, 'phone' => '966560000005', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'راشد ماجد مساعد', 'email' => 'rashid2025@gmail.com', 'age' => 8, 'phone' => '966560000002', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'راكان متعب عبدالله', 'email' => 'rakan2025@gmail.com', 'age' => 8, 'phone' => '9665600000015', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'سعد عبدالله محمد المحمود', 'email' => 'Saad2025@gmail.com', 'age' => 8, 'phone' => '9665600000019', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'سلطان حسين صويان', 'email' => 'sultan2025@gmail.com', 'age' => 8, 'phone' => '9665600000017', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'سلمان عبدالاله النزهان', 'email' => 'salman2025@gmail.com', 'age' => 8, 'phone' => '9665600000012', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'عبدالرحمن خالد الشبانات', 'email' => 'Dahmi2025@gmail.com', 'age' => 8, 'phone' => '9665600000020', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'عبدالعال محمد عبدالعال', 'email' => 'abdulaal2025@gmail.com', 'age' => 8, 'phone' => '966560000008', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'عبدالعزيز عبدالحكيم الدوخي', 'email' => 'azooz2025@gmail.com', 'age' => 8, 'phone' => '9665600000011', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'عثمان محمد عبدالعال', 'email' => 'othman2025@gmail.com', 'age' => 8, 'phone' => '966560000009', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'فهد محمد آل معدي', 'email' => 'fahad2025@gmail.com', 'age' => 8, 'phone' => '9665600000014', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'فيصل هادي القحطاني', 'email' => 'Faisal2025@gmail.com', 'age' => 8, 'phone' => '966560000004', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'مؤمن علاء محمد', 'email' => 'momin2025@gmail.com', 'age' => 8, 'phone' => '9665600000010', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'محمد جمال محمد ابو قادم', 'email' => 'M.Jamal2025@gmail.com', 'age' => 8, 'phone' => '96656000000', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'محمد سعيد الدوسري', 'email' => 'M.saeed2025@gmail.com', 'age' => 8, 'phone' => '9665600000018', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'محمد مصعب جمال محمد', 'email' => 'M.mosab2025@gmail.com', 'age' => 8, 'phone' => '966560000003', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'ناصر عبداللطيف الخريجي', 'email' => 'Naser2025@gmail.com', 'age' => 8, 'phone' => '966560000007', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'ناصر فهد ال هليل', 'email' => 'Nasir2025@gmail.com', 'age' => 8, 'phone' => '966560000001', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'هشام عبدالله محمد العقيل', 'email' => 'hesham2025@gmail.com', 'age' => 8, 'phone' => '9665600000010', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'يوسف عبدالله نائف العنزي', 'email' => 'Soso1991sa@hotmail.com', 'age' => 8, 'phone' => '966532725585', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'يوسف محمود البش', 'email' => 'yusuf2025@gmail.com', 'age' => 8, 'phone' => '9665600000016', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-'],

            // حلقة أشبال القرآن (المربي محمد أنور) - 23 students  
            ['name' => 'آسر محمد عبد الكريم', 'email' => 'As87@gmail.com', 'age' => 5, 'phone' => '9669658741358', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'أكرم محمد صلاح الدين', 'email' => '2A8@gmail.com', 'age' => 4, 'phone' => '966556740921', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'أنس فوزي عبد المقصود', 'email' => 'fddddf22@gmail.com', 'age' => 5, 'phone' => '96635676333', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'الياس مبارك الاسمري', 'email' => '2A5@gmail.com', 'age' => 6, 'phone' => '966558225280', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'انس ماللك عبدالله', 'email' => '00Ana0@gmail.com', 'age' => 10, 'phone' => '966547814541', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'تاج الدين صفوان', 'email' => 'aaa525@gmail.com', 'age' => 10, 'phone' => '966500000000', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'حمد علي القيسي', 'email' => '2A12@gmail.com', 'age' => 6, 'phone' => '966505956011', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'خالد عبدالرحمن القحطاني', 'email' => '10A13@gmail.com', 'age' => 18, 'phone' => '966506082457', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'خالد عبدالله الدوسري', 'email' => '2A3@gmail.com', 'age' => 6, 'phone' => '966540989557', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'زياد هادي بعجري', 'email' => '2A10@gmail.com', 'age' => 5, 'phone' => '966504232959', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'عبد الرحمن حسن الزيني', 'email' => 'Dah12@gmail.com', 'age' => 9, 'phone' => '966958741236', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'عبدالكريم طارق الورثان', 'email' => '2A0@gmail.com', 'age' => 7, 'phone' => '966556505581', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'عبدالله بدر الفارس', 'email' => '2A6@gmail.com', 'age' => 4, 'phone' => '966504416436', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'علي بدر الفارس', 'email' => '2A7@gmail.com', 'age' => 6, 'phone' => '966504416436', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'عمر محمود البش', 'email' => 'fgghh1@gmail.com', 'age' => 55, 'phone' => '9734445553', 'country' => 'Bahrain', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'فارس عبد الله العروان', 'email' => 'cgg33fffr@gmail.com', 'age' => 52, 'phone' => '37223556777', 'country' => 'Estonia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'كنان مالك الأهدل', 'email' => 'kinan@gmail.com', 'age' => 10, 'phone' => '9665600000021', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'ماجد حسين ال صويان', 'email' => '2A11@gmail.com', 'age' => 6, 'phone' => '966548332220', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'محمد العبد السلام', 'email' => 'dssss233@gmail.com', 'age' => 42, 'phone' => '962334444333', 'country' => 'Jordan', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'معتصم علي الشامي', 'email' => 'cggfffr@gmail.com', 'age' => 52, 'phone' => '9682355677773', 'country' => 'Oman', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'هاني أحمد شرف', 'email' => 'dweess233@gmail.com', 'age' => 42, 'phone' => '9663344444333', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'هشام إبراهيم العويرضي', 'email' => '2A9@gmail.com', 'age' => 4, 'phone' => '966590411588', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'يوسف أحمد أرباب', 'email' => 'yusuf01@gmail.com', 'age' => 10, 'phone' => '9665600000021', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],
            ['name' => 'يوسف سعيد ابراهيم', 'email' => 'fgghh@gmail.com', 'age' => 55, 'phone' => '9664445553', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-'],

            // حلقة القدوات - 25 students
            ['name' => 'إبراهيم صالح المعدي', 'email' => 'barhoom.S20@gmail.com', 'age' => 44, 'phone' => '9665600000062', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'أنس بن سليمان عيسى', 'email' => '10A26@gmail.com', 'age' => 19, 'phone' => '966509763628', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'ابراهيم شفيق غانم', 'email' => '10A12@gmail.com', 'age' => 18, 'phone' => '966533769077', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'ابراهيم علي ابراهيم', 'email' => 'ia7502379@gmail.com', 'age' => 23, 'phone' => '966573245104', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'بدر بن خالد الهليل', 'email' => 'badar20@gmail.com', 'age' => 44, 'phone' => '9665600000055', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'تركي بن شوعي علي غروي', 'email' => 'turki-1400@hotmail.com', 'age' => 45, 'phone' => '966560877693', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'حمد ناصر العتيق', 'email' => 'Hammad.N20@gmail.com', 'age' => 44, 'phone' => '9665600000060', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'خالد محمد راجحي', 'email' => '01Khalid000@gmail.com', 'age' => 20, 'phone' => '9665698741245', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'زيد بن عبد الله العثمان', 'email' => 'ziad.Abod20@gmail.com', 'age' => 44, 'phone' => '9665600000063', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'سلطان أحمد محمد', 'email' => '10A23@gmail.com', 'age' => 18, 'phone' => '966504444707', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'صلاح بن يوسف', 'email' => 'salhrbi154@gmail.com', 'age' => 19, 'phone' => '966550359443', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'عبد الرحمن محمد ناجي', 'email' => 'dahmi.Naji@gmail.com', 'age' => 44, 'phone' => '9665600000056', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'عبد الله بن إدريس إدريس', 'email' => '01Aboodidres000@gmail.com', 'age' => 20, 'phone' => '9665698741245', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'عبدالرحمن شفيق غانم', 'email' => '10A6@gmail.com', 'age' => 18, 'phone' => '966558263960', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'عبدالله مسلم المسلم', 'email' => '10A1@gmail.com', 'age' => 20, 'phone' => '96650042086', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'علي بن سليمان عيسى', 'email' => '10A2@gmail.com', 'age' => 20, 'phone' => '966548984174', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'علي بن محمد النعمي', 'email' => 'Ali.M20@gmail.com', 'age' => 44, 'phone' => '9665600000061', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'عمر عماد محمود ابو سيف', 'email' => 'eabusaif15@gmail.com', 'age' => 16, 'phone' => '966563250611', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'محمد البراء فرج', 'email' => 'Mb.baraa20@gmail.com', 'age' => 44, 'phone' => '9665600000059', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'محمد بندر السبيعي', 'email' => 'ml503lb@gmail.com', 'age' => 20, 'phone' => '966552665591', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'محمد حسن الزيني', 'email' => 'Tamimi2003@hotmail.com', 'age' => 18, 'phone' => '966507013360', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'مهند محمد خردلي', 'email' => '10A4@gmail.com', 'age' => 17, 'phone' => '966538604600', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'موسى عيسى الحسين', 'email' => 'musa00332211@gmail.com', 'age' => 20, 'phone' => '966558450091', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'ياسر عبد الله الجنيدل', 'email' => 'Yasir.abod20@gmail.com', 'age' => 44, 'phone' => '9665600000058', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],
            ['name' => 'يعقوب بن محمد الحكمي', 'email' => '10A10@gmail.com', 'age' => 19, 'phone' => '966570187920', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-'],

            // حلقة المربي حذيفة المعيوف - 11 students
            ['name' => 'أحمد عبدالعليم الأهدل', 'email' => '02Ahmedahdal00@gamil.com', 'age' => 11, 'phone' => '966541245879', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'امير بدر الفارس', 'email' => 'aaa534@gmail.com', 'age' => 10, 'phone' => '966500000000', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'خالد هادي بعجري', 'email' => '3A15@gmail.com', 'age' => 10, 'phone' => '966556033856', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'طلال ناصر العتيق', 'email' => 'Talal.N230@gmail.com', 'age' => 13, 'phone' => '9665600000067', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'عبدالعزيز مشعل الخزاعي', 'email' => 'tbsm_2009@hotmail.com', 'age' => 7, 'phone' => '966508391520', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'عمر متعب الهديان', 'email' => '3A8@gmail.com', 'age' => 10, 'phone' => '966554441462', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'غسان أحمد جعفري', 'email' => 'Ghassan.A20@gmail.com', 'age' => 13, 'phone' => '9665600000069', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'فهد محمد الشريدة', 'email' => '3A1@gmail.com', 'age' => 10, 'phone' => '966546537734', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'قصي احمد الجعفري', 'email' => 'aaa530@gmail.com', 'age' => 10, 'phone' => '966500000000', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'ماجد حسين نفل ال صويان', 'email' => 'n.m.w1401@gmail.com', 'age' => 9, 'phone' => '966548332220', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
            ['name' => 'ناصر زيد الهليل', 'email' => 'nasir.ZD20@gmail.com', 'age' => 13, 'phone' => '9665600000061', 'country' => 'Saudi Arabia', 'circle_name' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-'],
        ];

        // Create all students and assign them to their circles
        foreach ($students as $studentData) {
            $country = $getCountryByName($studentData['country']);
            
            $student = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password123'),
                'phone' => $studentData['phone'],
                'age' => $studentData['age'],
                'gender' => 'male',
                'role' => 'student',
                'country_id' => $country->id,
                'is_active' => true,
            ]);

            // Assign student to their circle
            $circle = $circles[$studentData['circle_name']];
            CircleStudent::create([
                'circle_id' => $circle->id,
                'student_id' => $student->id,
            ]);
        }
    }
}