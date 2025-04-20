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
                'ar' => 'أكاديمية إتقان الإلكترونية',
                'en' => 'Itqan Electronic Academy'
            ],
            'welcome_message' => [
                'ar' => 'أهلاً بك في أكاديمية إتقان الإلكترونية لتعليم القرآن الكريم وعلومه',
                'en' => 'Welcome to Itqan Electronic Academy for teaching the Holy Quran and its sciences'
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
                'ar' => 'اكتشف الميزات المتميزة التي تقدمها أكاديمية إتقان الإلكترونية',
                'en' => 'Discover the outstanding features offered by Itqan Electronic Academy'
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
        ];
    }
} 