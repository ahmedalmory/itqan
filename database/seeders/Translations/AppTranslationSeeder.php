<?php

namespace Database\Seeders\Translations;

class AppTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     * 
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // App specific
            'app_name' => [
                'ar' => 'مسجد ناصر بن علي الخميس',
                'en' => 'Nasser Bin Ali Al-Khamees Mosque'
            ],
            'app_name_full' => [
                'ar' => 'مسجد ناصر بن علي الخميس - مجمع إتقان الرقمي للقرآن الكريم',
                'en' => 'Nasser Bin Ali Al-Khamees Mosque - Itqan Digital Complex for the Holy Quran'
            ],
            'welcome_message' => [
                'ar' => 'أهلاً بك في مسجد ناصر بن علي الخميس - مجمع إتقان الرقمي لتعليم القرآن الكريم وعلومه',
                'en' => 'Welcome to Nasser Bin Ali Al-Khamees Mosque - Itqan Digital Complex for teaching the Holy Quran and its sciences'
            ],
            'pages' => [
                'ar' => 'صفحات',
                'en' => 'Pages'
            ],
            'recent_activities' => [
                'ar' => 'الأنشطة الأخيرة',
                'en' => 'Recent Activities'
            ],
            'upcoming_schedule' => [
                'ar' => 'الجدول القادم',
                'en' => 'Upcoming Schedule'
            ],
            'daily_report_submitted' => [
                'ar' => 'تم تقديم التقرير اليومي',
                'en' => 'Daily Report Submitted'
            ],
            'upcoming_assignments' => [
                'ar' => 'المهام القادمة',
                'en' => 'Upcoming Assignments'
            ],
            'view_all_assignments' => [
                'ar' => 'عرض جميع المهام',
                'en' => 'View All Assignments'
            ],
            'recent_activity' => [
                'ar' => 'النشاط الأخير',
                'en' => 'Recent Activity'
            ],
            
            // Actions
            'save' => [
                'ar' => 'حفظ',
                'en' => 'Save'
            ],
            'cancel' => [
                'ar' => 'إلغاء',
                'en' => 'Cancel'
            ],
            'edit' => [
                'ar' => 'تعديل',
                'en' => 'Edit'
            ],
            'delete' => [
                'ar' => 'حذف',
                'en' => 'Delete'
            ],
            'view' => [
                'ar' => 'عرض',
                'en' => 'View'
            ],
            'create' => [
                'ar' => 'إنشاء',
                'en' => 'Create'
            ],
            'search' => [
                'ar' => 'بحث',
                'en' => 'Search'
            ],
            'submit' => [
                'ar' => 'إرسال',
                'en' => 'Submit'
            ],
            'update' => [
                'ar' => 'تحديث',
                'en' => 'Update'
            ],
            'activate' => [
                'ar' => 'تفعيل',
                'en' => 'Activate'
            ],
            'deactivate' => [
                'ar' => 'تعطيل',
                'en' => 'Deactivate'
            ],
            'reset' => [
                'ar' => 'إعادة تعيين',
                'en' => 'Reset'
            ],
            
            // Welcome Page Features
            'our_features' => [
                'ar' => 'مميزاتنا',
                'en' => 'Our Features'
            ],
            'features_description' => [
                'ar' => 'اكتشف الميزات المتميزة التي يقدمها مسجد ناصر بن علي الخميس - مجمع إتقان الرقمي',
                'en' => 'Discover the outstanding features offered by Nasser Bin Ali Al-Khamees Mosque - Itqan Digital Complex'
            ],
            'feature_quran_title' => [
                'ar' => 'تعليم القرآن',
                'en' => 'Quran Education'
            ],
            'feature_quran_description' => [
                'ar' => 'تعلم تلاوة وحفظ القرآن الكريم بإتقان مع منهجية تعليمية متميزة',
                'en' => 'Learn to recite and memorize the Holy Quran perfectly with an outstanding educational methodology'
            ],
            'feature_teachers_title' => [
                'ar' => 'معلمون متميزون',
                'en' => 'Excellent Teachers'
            ],
            'feature_teachers_description' => [
                'ar' => 'نخبة من المعلمين المؤهلين ذوي الخبرة في تعليم القرآن الكريم',
                'en' => 'A selection of qualified teachers with experience in teaching the Holy Quran'
            ],
            'feature_tracking_title' => [
                'ar' => 'متابعة التقدم',
                'en' => 'Progress Tracking'
            ],
            'feature_tracking_description' => [
                'ar' => 'نظام متكامل لمتابعة تقدم الطلاب وتقييم أدائهم في الحفظ والتلاوة',
                'en' => 'Integrated system for tracking student progress and evaluating their performance in memorization and recitation'
            ],
            
            // Call to Action Section
            'cta_title' => [
                'ar' => 'انضم إلينا اليوم وابدأ رحلتك مع القرآن',
                'en' => 'Join Us Today and Start Your Journey with the Quran'
            ],
            'cta_description' => [
                'ar' => 'سجل الآن واستمتع بتجربة تعليمية فريدة في حفظ وتلاوة القرآن الكريم',
                'en' => 'Register now and enjoy a unique learning experience in memorizing and reciting the Holy Quran'
            ],
            'join_now' => [
                'ar' => 'انضم الآن',
                'en' => 'Join Now'
            ],
            
            // Time preference
            'preferred_time' => [
                'ar' => 'الوقت المفضل',
                'en' => 'Preferred Time'
            ],
            'select_preferred_time' => [
                'ar' => 'اختر الوقت المفضل',
                'en' => 'Select Preferred Time'
            ],
            'after_fajr' => [
                'ar' => 'بعد الفجر',
                'en' => 'After Fajr'
            ],
            'after_dhuhr' => [
                'ar' => 'بعد الظهر',
                'en' => 'After Dhuhr'
            ],
            'after_asr' => [
                'ar' => 'بعد العصر',
                'en' => 'After Asr'
            ],
            'after_maghrib' => [
                'ar' => 'بعد المغرب',
                'en' => 'After Maghrib'
            ],
            'after_isha' => [
                'ar' => 'بعد العشاء',
                'en' => 'After Isha'
            ],

            // Academy specific translations
            'nasser_mosque' => [
                'ar' => 'مسجد ناصر بن علي الخميس',
                'en' => 'Nasser Bin Ali Al-Khamees Mosque'
            ],
            'itqan_digital_complex' => [
                'ar' => 'مجمع إتقان الرقمي',
                'en' => 'Itqan Digital Complex'
            ],
            'department_z11' => [
                'ar' => 'Z11 مجمع إ',
                'en' => 'Z11 Complex E'
            ],
            'young_cubs_circle' => [
                'ar' => 'حلقة أشبال القرآن',
                'en' => 'Young Cubs Quran Circle'
            ],
            'role_models_circle' => [
                'ar' => 'حلقة القدوات',
                'en' => 'Role Models Circle'
            ],
            'teacher_anas' => [
                'ar' => 'المربي أنس سليمان',
                'en' => 'Teacher Anas Suleiman'
            ],
            'teacher_mohamed_anwar' => [
                'ar' => 'المربي محمد أنور',
                'en' => 'Teacher Mohamed Anwar'
            ],
            'teacher_osama' => [
                'ar' => 'المربي أسامة التميمي',
                'en' => 'Teacher Osama Al-Tamimi'
            ],
            'teacher_hudhaifa' => [
                'ar' => 'المربي حذيفة المعيوف',
                'en' => 'Teacher Hudhaifa Al-Maaiof'
            ],
            'teacher_issam' => [
                'ar' => 'المربي عصام سعد',
                'en' => 'Teacher Issam Saad'
            ],
            'teacher_ali' => [
                'ar' => 'المربي علي سليمان',
                'en' => 'Teacher Ali Suleiman'
            ],
            'teacher_mohamed_zeni' => [
                'ar' => 'المربي محمد الزيني',
                'en' => 'Teacher Mohamed Al-Zeni'
            ],
            'teacher_ahmed_musa' => [
                'ar' => 'المعلم أحمد محمد موسى',
                'en' => 'Teacher Ahmed Mohamed Musa'
            ],
            'teacher_mohamed_abuzaher' => [
                'ar' => 'المعلم محمد أبو زاهر',
                'en' => 'Teacher Mohamed Abu Zaher'
            ],
            'circle_times' => [
                'ar' => 'أوقات الحلقات',
                'en' => 'Circle Times'
            ],
            'asr_period' => [
                'ar' => 'فترة العصر',
                'en' => 'Asr Period'
            ],
            'maghrib_period' => [
                'ar' => 'فترة المغرب',
                'en' => 'Maghrib Period'
            ],
            'age_group_5_8' => [
                'ar' => '5 - 8 سنوات',
                'en' => '5 - 8 years old'
            ],
            'age_group_9_12' => [
                'ar' => '9 - 12 سنة',
                'en' => '9 - 12 years old'
            ],
            'age_group_high_school_plus' => [
                'ar' => 'ثالث ثانوي فما فوق',
                'en' => 'Third year high school and above'
            ],
            'sixth_grade_first_middle' => [
                'ar' => 'سادس ابتدائي وأول متوسط',
                'en' => 'Sixth grade and first middle school'
            ],
            'second_middle_school' => [
                'ar' => 'المتوسطة الثانية',
                'en' => 'Second middle school'
            ],
            'third_middle_high_school' => [
                'ar' => 'ثالث متوسط والثانوي',
                'en' => 'Third middle and high school'
            ],
            'academy_motto' => [
                'ar' => 'نحو إتقان القرآن الكريم',
                'en' => 'Towards Mastering the Holy Quran'
            ],
            'academy_vision' => [
                'ar' => 'رؤيتنا: إعداد جيل قرآني متميز يحمل كتاب الله في قلبه ويطبقه في حياته',
                'en' => 'Our Vision: Preparing a distinguished Quranic generation that carries the Book of Allah in their hearts and applies it in their lives'
            ],
            'academy_mission' => [
                'ar' => 'رسالتنا: تقديم تعليم قرآني متميز باستخدام أحدث الوسائل التقنية والتربوية',
                'en' => 'Our Mission: Providing distinguished Quranic education using the latest technical and educational methods'
            ],
        ];
    }
} 