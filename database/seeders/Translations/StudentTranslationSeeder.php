<?php

namespace Database\Seeders\Translations;

class StudentTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     * 
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // Student Dashboard
            'memorization_progress' => [
                'ar' => 'تقدم الحفظ',
                'en' => 'Memorization Progress'
            ],
            'quran_memorization' => [
                'ar' => 'حفظ القرآن',
                'en' => 'Quran Memorization'
            ],
            'earned_points' => [
                'ar' => 'النقاط المكتسبة',
                'en' => 'Earned Points'
            ],
            'attendance' => [
                'ar' => 'الحضور',
                'en' => 'Attendance'
            ],
            'my_progress' => [
                'ar' => 'تقدمي',
                'en' => 'My Progress'
            ],
            'active_students' => [
                'ar' => 'الطلاب النشطين',
                'en' => 'Active Students'
            ],
            'enrolled_students' => [
                'ar' => 'الطلاب المسجلين',
                'en' => 'Enrolled Students'
            ],
            'students_count' => [
                'ar' => 'عدد الطلاب',
                'en' => 'Students Count'
            ],
            'view_points' => [
                'ar' => 'عرض النقاط',
                'en' => 'View Points'
            ],
            'view_attendance' => [
                'ar' => 'عرض الحضور',
                'en' => 'View Attendance'
            ],
            'total_memorization' => [
                'ar' => 'إجمالي الحفظ',
                'en' => 'Total Memorization'
            ],
            'parts' => [
                'ar' => 'أجزاء',
                'en' => 'Parts'
            ],
            'total_revision' => [
                'ar' => 'إجمالي المراجعة',
                'en' => 'Total Revision'
            ],
            'average_grade' => [
                'ar' => 'متوسط الدرجة',
                'en' => 'Average Grade'
            ],
            'out_of_10' => [
                'ar' => 'من 10',
                'en' => 'Out of 10'
            ],
            'monthly_progress' => [
                'ar' => 'التقدم الشهري',
                'en' => 'Monthly Progress'
            ],
            'month' => [
                'ar' => 'الشهر',
                'en' => 'Month'
            ],
            'no_monthly_progress_data' => [
                'ar' => 'لا توجد بيانات تقدم شهرية',
                'en' => 'No monthly progress data'
            ],
            'points_by_circle' => [
                'ar' => 'النقاط حسب الحلقة',
                'en' => 'Points by Circle'
            ],
            'no_points_earned_yet' => [
                'ar' => 'لم يتم كسب أي نقاط حتى الآن',
                'en' => 'No points earned yet'
            ],
            'no_reports_submitted_yet' => [
                'ar' => 'لم يتم تقديم تقارير حتى الآن',
                'en' => 'No reports submitted yet'
            ],
            'circle_session' => [
                'ar' => 'جلسة الحلقة',
                'en' => 'Circle Session'
            ],
            'new_student_registered' => [
                'ar' => 'تسجيل طالب جديد',
                'en' => 'New Student Registered'
            ],
            'you_memorized' => [
                'ar' => 'لقد حفظت',
                'en' => 'You Memorized'
            ],
            'verses' => [
                'ar' => 'آيات',
                'en' => 'Verses'
            ],
            'memorize_surah' => [
                'ar' => 'حفظ سورة',
                'en' => 'Memorize Surah'
            ],
            
            // Quran related
            'surahs' => [
                'ar' => 'السور',
                'en' => 'Surahs'
            ],
            'recitation' => [
                'ar' => 'التلاوة',
                'en' => 'Recitation'
            ],
            'memorization' => [
                'ar' => 'الحفظ',
                'en' => 'Memorization'
            ],
            'revision' => [
                'ar' => 'المراجعة',
                'en' => 'Revision'
            ],
            
            // Subscription related translations
            'my_subscriptions' => [
                'ar' => 'اشتراكاتي',
                'en' => 'My Subscriptions'
            ],
            'new_subscription' => [
                'ar' => 'اشتراك جديد',
                'en' => 'New Subscription'
            ],
            'id' => [
                'ar' => 'المعرف',
                'en' => 'ID'
            ],
            'plan' => [
                'ar' => 'الخطة',
                'en' => 'Plan'
            ],
            'amount' => [
                'ar' => 'المبلغ',
                'en' => 'Amount'
            ],
            'start_date' => [
                'ar' => 'تاريخ البدء',
                'en' => 'Start Date'
            ],
            'expiry_date' => [
                'ar' => 'تاريخ الانتهاء',
                'en' => 'Expiry Date'
            ],
            'monthly_plan' => [
                'ar' => 'خطة شهرية',
                'en' => 'Monthly Plan'
            ],
            'quarterly_plan' => [
                'ar' => 'خطة ربع سنوية',
                'en' => 'Quarterly Plan'
            ],
            'biannual_plan' => [
                'ar' => 'خطة نصف سنوية',
                'en' => 'Biannual Plan'
            ],
            'annual_plan' => [
                'ar' => 'خطة سنوية',
                'en' => 'Annual Plan'
            ],
            'pending' => [
                'ar' => 'قيد الانتظار',
                'en' => 'Pending'
            ],
            'active' => [
                'ar' => 'نشط',
                'en' => 'Active'
            ],
            'inactive' => [
                'ar' => 'غير نشط',
                'en' => 'Inactive'
            ],
            'Active Account' => [
                'ar' => 'حساب نشط',
                'en' => 'Active Account'
            ],
            'expired' => [
                'ar' => 'منتهي',
                'en' => 'Expired'
            ],
            'cancelled' => [
                'ar' => 'ملغي',
                'en' => 'Cancelled'
            ],
            'are_you_sure_cancel_subscription' => [
                'ar' => 'هل أنت متأكد من إلغاء هذا الاشتراك؟',
                'en' => 'Are you sure you want to cancel this subscription?'
            ],
            'no_subscriptions_found' => [
                'ar' => 'لم يتم العثور على اشتراكات',
                'en' => 'No subscriptions found'
            ],
            'payments' => [
                'ar' => 'المدفوعات',
                'en' => 'Payments'
            ],
        ];
    }
} 