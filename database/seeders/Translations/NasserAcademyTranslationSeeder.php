<?php

namespace Database\Seeders\Translations;

class NasserAcademyTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations specific to Nasser Al-Khamees Academy
     * 
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // Academy Identity
            'nasser_academy_full_name' => [
                'ar' => 'مسجد ناصر بن علي الخميس - مجمع إتقان الرقمي للقرآن الكريم',
                'en' => 'Nasser Bin Ali Al-Khamees Mosque - Itqan Digital Complex for the Holy Quran'
            ],
            'nasser_academy_short' => [
                'ar' => 'مسجد ناصر بن علي الخميس',
                'en' => 'Nasser Al-Khamees Mosque'
            ],
            'academy_department_name' => [
                'ar' => 'مسجد ناصر بن علي الخميس - Z11 مجمع إ',
                'en' => 'Nasser Al-Khamees Mosque - Z11 Complex E'
            ],

            // Study Circles - Exact names from CSV
            'circle_ashbal_anas' => [
                'ar' => 'حلقة أشبال القرآن (المربي أنس سليمان) 5 - 8 سنوات -فترة العصر-',
                'en' => 'Young Cubs Quran Circle (Teacher Anas Suleiman) 5-8 years - Asr period'
            ],
            'circle_ashbal_mohamed' => [
                'ar' => 'حلقة أشبال القرآن (المربي محمد أنور) 5 - 8 سنوات -فترة العصر-',
                'en' => 'Young Cubs Quran Circle (Teacher Mohamed Anwar) 5-8 years - Asr period'
            ],
            'circle_qudwat' => [
                'ar' => 'حلقة القدوات- ثالث ثانوي فما فوق -فترة المغرب-',
                'en' => 'Role Models Circle - Third year high school and above - Maghrib period'
            ],
            'circle_hudhaifa' => [
                'ar' => 'حلقة المربي حذيفة المعيوف (9 - 12) -فترة العصر-',
                'en' => 'Teacher Hudhaifa Al-Maaiof Circle (9-12) - Asr period'
            ],
            'circle_issam' => [
                'ar' => 'حلقة المربي عصام سعد (9 - 12 سنة)-فترة العصر-',
                'en' => 'Teacher Issam Saad Circle (9-12 years) - Asr period'
            ],
            'circle_ali_asr' => [
                'ar' => 'حلقة المربي علي سليمان( 9 - 12 سنة) -فترة العصر-',
                'en' => 'Teacher Ali Suleiman Circle (9-12 years) - Asr period'
            ],
            'circle_mohamed_zeni' => [
                'ar' => 'حلقة المربي محمد الزيني (9 - 12 سنة)-فترة المغرب-',
                'en' => 'Teacher Mohamed Al-Zeni Circle (9-12 years) - Maghrib period'
            ],
            'circle_ahmed_musa' => [
                'ar' => 'حلقة المعلم أحمد محمد - سادس ابتدائي وأول متوسط -فترة المغرب-',
                'en' => 'Teacher Ahmed Mohamed Circle - Sixth grade and first middle school - Maghrib period'
            ],
            'circle_ali_middle' => [
                'ar' => 'حلقة المعلم علي سليمان - المتوسطة الثانية -فترة المغرب-',
                'en' => 'Teacher Ali Suleiman Circle - Second middle school - Maghrib period'
            ],
            'circle_abuzaher' => [
                'ar' => 'حلقة المعلم محمد أبو زاهر - ثالث متوسط والثانوي -فترة المغرب-',
                'en' => 'Teacher Mohamed Abu Zaher Circle - Third middle and high school - Maghrib period'
            ],

            // Teachers - Full names from CSV
            'teacher_anas_full' => [
                'ar' => 'انس سليمان عيسى',
                'en' => 'Anas Suleiman Issa'
            ],
            'teacher_mohamed_anwar_full' => [
                'ar' => 'محمد أنور عبد الباقي السيد',
                'en' => 'Mohamed Anwar Abdel Baqi Al-Sayed'
            ],
            'teacher_osama_full' => [
                'ar' => 'أسامة بن سعود التميمي',
                'en' => 'Osama Bin Saud Al-Tamimi'
            ],
            'teacher_hudhaifa_full' => [
                'ar' => 'حذيفة بن احمد المعيوف',
                'en' => 'Hudhaifa Bin Ahmed Al-Maaiof'
            ],
            'teacher_issam_full' => [
                'ar' => 'عصام سعد أحمد سعد',
                'en' => 'Issam Saad Ahmed Saad'
            ],
            'teacher_ali_full' => [
                'ar' => 'علي بن سليمان',
                'en' => 'Ali Bin Suleiman'
            ],
            'teacher_mohamed_zeni_full' => [
                'ar' => 'محمد الزيني',
                'en' => 'Mohamed Al-Zeni'
            ],
            'teacher_ahmed_musa_full' => [
                'ar' => 'أحمد محمد موسى',
                'en' => 'Ahmed Mohamed Musa'
            ],
            'teacher_abuzaher_full' => [
                'ar' => 'محمد ابو زاهر موسى',
                'en' => 'Mohamed Abu Zaher Musa'
            ],

            // Academy Features and Descriptions
            'digital_quran_complex' => [
                'ar' => 'مجمع رقمي متطور لتعليم القرآن الكريم',
                'en' => 'Advanced digital complex for teaching the Holy Quran'
            ],
            'qualified_teachers' => [
                'ar' => 'نخبة من المعلمين المؤهلين من السعودية ومصر وسوريا',
                'en' => 'Elite qualified teachers from Saudi Arabia, Egypt, and Syria'
            ],
            'age_based_circles' => [
                'ar' => 'حلقات مقسمة حسب الأعمار والمستويات',
                'en' => 'Circles divided by age groups and levels'
            ],
            'multiple_time_slots' => [
                'ar' => 'أوقات متعددة (العصر والمغرب) لتناسب الجميع',
                'en' => 'Multiple time slots (Asr and Maghrib) to suit everyone'
            ],

            // Time Periods
            'asr_time_description' => [
                'ar' => 'حلقات فترة العصر - مناسبة للأطفال بعد المدرسة',
                'en' => 'Asr period circles - suitable for children after school'
            ],
            'maghrib_time_description' => [
                'ar' => 'حلقات فترة المغرب - مناسبة للطلاب الأكبر سناً',
                'en' => 'Maghrib period circles - suitable for older students'
            ],

            // Age Groups
            'cubs_age_description' => [
                'ar' => 'الأشبال (5-8 سنوات) - تأسيس قرآني للصغار',
                'en' => 'Cubs (5-8 years) - Quranic foundation for young children'
            ],
            'intermediate_age_description' => [
                'ar' => 'المتوسط (9-12 سنة) - تطوير المهارات القرآنية',
                'en' => 'Intermediate (9-12 years) - Developing Quranic skills'
            ],
            'advanced_age_description' => [
                'ar' => 'المتقدم (13+ سنة) - إتقان القرآن والتجويد',
                'en' => 'Advanced (13+ years) - Mastering Quran and Tajweed'
            ],

            // Academy Statistics
            'total_circles' => [
                'ar' => 'إجمالي الحلقات',
                'en' => 'Total Circles'
            ],
            'total_teachers' => [
                'ar' => 'إجمالي المعلمين',
                'en' => 'Total Teachers'
            ],
            'teachers_count' => [
                'ar' => '9 معلمين مؤهلين',
                'en' => '9 Qualified Teachers'
            ],
            'total_students' => [
                'ar' => 'إجمالي الطلاب',
                'en' => 'Total Students'
            ],
            'countries_represented' => [
                'ar' => 'الدول الممثلة',
                'en' => 'Countries Represented'
            ],

            // Contact and Location
            'academy_location' => [
                'ar' => 'المملكة العربية السعودية',
                'en' => 'Saudi Arabia'
            ],
            'contact_information' => [
                'ar' => 'معلومات التواصل',
                'en' => 'Contact Information'
            ],

            // Welcome Messages
            'welcome_to_nasser_academy' => [
                'ar' => 'أهلاً وسهلاً بك في مسجد ناصر بن علي الخميس',
                'en' => 'Welcome to Nasser Bin Ali Al-Khamees Mosque'
            ],
            'join_our_quran_journey' => [
                'ar' => 'انضم إلى رحلتنا في تعلم وحفظ القرآن الكريم',
                'en' => 'Join our journey in learning and memorizing the Holy Quran'
            ],
            'excellence_in_quran_education' => [
                'ar' => 'التميز في تعليم القرآن الكريم',
                'en' => 'Excellence in Quran Education'
            ],

            // Academy Values
            'our_values' => [
                'ar' => 'قيمنا',
                'en' => 'Our Values'
            ],
            'value_excellence' => [
                'ar' => 'التميز في التعليم القرآني',
                'en' => 'Excellence in Quranic education'
            ],
            'value_dedication' => [
                'ar' => 'الإخلاص في خدمة كتاب الله',
                'en' => 'Dedication in serving the Book of Allah'
            ],
            'value_innovation' => [
                'ar' => 'الابتكار في الوسائل التعليمية',
                'en' => 'Innovation in educational methods'
            ],
            'value_community' => [
                'ar' => 'بناء مجتمع قرآني متماسك',
                'en' => 'Building a cohesive Quranic community'
            ],

            // Success Metrics
            'students_memorized' => [
                'ar' => 'طلاب أتموا الحفظ',
                'en' => 'Students who completed memorization'
            ],
            'years_of_experience' => [
                'ar' => 'سنوات من الخبرة',
                'en' => 'Years of experience'
            ],
            'success_rate' => [
                'ar' => 'معدل النجاح',
                'en' => 'Success rate'
            ],
        ];
    }
} 