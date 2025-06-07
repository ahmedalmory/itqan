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
                'ar' => 'أوجه',
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
            // Points Management
            'points_management' => [
                'ar' => 'إدارة النقاط',
                'en' => 'Points Management'
            ],
            'manage_points' => [
                'ar' => 'إدارة النقاط',
                'en' => 'Manage Points'
            ],
            'bulk_assign_points' => [
                'ar' => 'إضافة نقاط للجميع',
                'en' => 'Bulk Assign Points'
            ],
            'bulk_assign_points_for_circle' => [
                'ar' => 'إضافة نقاط لحلقة',
                'en' => 'Bulk Assign Points for Circle'
            ],
            'quick_assign' => [
                'ar' => 'إضافة سريعة',
                'en' => 'Quick Assign'
            ],
            'points_for_all' => [
                'ar' => 'نقاط للجميع',
                'en' => 'Points for All'
            ],
            'apply_to_all' => [
                'ar' => 'تطبيق على الجميع',
                'en' => 'Apply to All'
            ],
            'student_name' => [
                'ar' => 'اسم الطالب',
                'en' => 'Student Name'
            ],
            'current_points' => [
                'ar' => 'النقاط الحالية',
                'en' => 'Current Points'
            ],
            'points_to_add' => [
                'ar' => 'النقاط المراد إضافتها',
                'en' => 'Points to Add'
            ],
            'save_changes' => [
                'ar' => 'حفظ التغييرات',
                'en' => 'Save Changes'
            ],
            'manage_points_for' => [
                'ar' => 'إدارة نقاط',
                'en' => 'Manage Points for'
            ],
            'points_help_text' => [
                'ar' => 'أدخل قيمة موجبة لإضافة نقاط أو قيمة سالبة لخصم نقاط',
                'en' => 'Enter a positive value to add points or a negative value to deduct points'
            ],
            'select_reason' => [
                'ar' => 'اختر السبب',
                'en' => 'Select Reason'
            ],
            'daily_memorization' => [
                'ar' => 'الحفظ اليومي',
                'en' => 'Daily Memorization'
            ],
            'participation' => [
                'ar' => 'المشاركة',
                'en' => 'Participation'
            ],
            'good_behavior' => [
                'ar' => 'حسن السلوك',
                'en' => 'Good Behavior'
            ],
            'extra_activities' => [
                'ar' => 'أنشطة إضافية',
                'en' => 'Extra Activities'
            ],
            'absence' => [
                'ar' => 'الغياب',
                'en' => 'Absence'
            ],
            'misconduct' => [
                'ar' => 'سوء السلوك',
                'en' => 'Misconduct'
            ],
            'other' => [
                'ar' => 'أخرى',
                'en' => 'Other'
            ],
            'points_updated_successfully' => [
                'ar' => 'تم تحديث النقاط بنجاح',
                'en' => 'Points updated successfully'
            ],
            'error_updating_points' => [
                'ar' => 'حدث خطأ أثناء تحديث النقاط',
                'en' => 'Error updating points'
            ],
            'unauthorized_action' => [
                'ar' => 'غير مصرح بهذا الإجراء',
                'en' => 'Unauthorized action'
            ],
            'student_not_in_circle' => [
                'ar' => 'الطالب غير مسجل في هذه الحلقة',
                'en' => 'Student is not in this circle'
            ],
            // New translations
            'welcome_message' => [
                'ar' => 'مرحباً بك',
                'en' => 'Welcome'
            ],
            'circle_info' => [
                'ar' => 'معلومات الحلقة',
                'en' => 'Circle Information'
            ],
            'not_assigned' => [
                'ar' => 'غير محدد',
                'en' => 'Not Assigned'
            ],
            'not_specified' => [
                'ar' => 'غير محدد',
                'en' => 'Not Specified'
            ],
            'join_whatsapp_group' => [
                'ar' => 'انضم لمجموعة الواتساب',
                'en' => 'Join WhatsApp Group'
            ],
            'join_telegram_group' => [
                'ar' => 'انضم لمجموعة التلجرام',
                'en' => 'Join Telegram Group'
            ],
            'not_enrolled_in_circle' => [
                'ar' => 'أنت غير مسجل في أي حلقة حالياً.',
                'en' => 'You are not enrolled in any circle.'
            ],
            'browse_circles' => [
                'ar' => 'تصفح الحلقات المتاحة',
                'en' => 'Browse Available Circles'
            ],
            'subscription_info' => [
                'ar' => 'معلومات الاشتراك',
                'en' => 'Subscription Information'
            ],
            'lessons' => [
                'ar' => 'درس',
                'en' => 'Lessons'
            ],
            'months' => [
                'ar' => 'شهر',
                'en' => 'Months'
            ],
            'paid' => [
                'ar' => 'مدفوع',
                'en' => 'Paid'
            ],
            'days_remaining' => [
                'ar' => 'الأيام المتبقية',
                'en' => 'Days Remaining'
            ],
            'days' => [
                'ar' => 'يوم',
                'en' => 'Days'
            ],
            'expiring_soon' => [
                'ar' => 'ينتهي قريباً',
                'en' => 'Expiring Soon'
            ],
            'view_subscription_details' => [
                'ar' => 'عرض تفاصيل الاشتراك',
                'en' => 'View Subscription Details'
            ],
            'renew_subscription' => [
                'ar' => 'تجديد الاشتراك',
                'en' => 'Renew Subscription'
            ],
            'no_active_subscription' => [
                'ar' => 'لا يوجد اشتراك نشط',
                'en' => 'No Active Subscription'
            ],
            'please_subscribe_to_continue' => [
                'ar' => 'يرجى الاشتراك للمتابعة',
                'en' => 'Please Subscribe to Continue'
            ],
            'available_plans' => [
                'ar' => 'الخطط المتاحة',
                'en' => 'Available Plans'
            ],
            'currency' => [
                'ar' => 'ريال',
                'en' => 'SAR'
            ],
            'subscribe_now' => [
                'ar' => 'اشترك الآن',
                'en' => 'Subscribe Now'
            ],
            'no_plans_available' => [
                'ar' => 'لا توجد خطط متاحة',
                'en' => 'No Plans Available'
            ],
            'view_all_subscription_options' => [
                'ar' => 'عرض جميع خيارات الاشتراك',
                'en' => 'View All Subscription Options'
            ],
            'points' => [
                'ar' => 'النقاط',
                'en' => 'Points'
            ],
            'back_to_subscriptions' => [
                'ar' => 'العودة إلى الاشتراكات',
                'en' => 'Back to Subscriptions'
            ],
            'select_circle' => [
                'ar' => 'اختر الحلقة',
                'en' => 'Select Circle'
            ],
            'select_subscription_plan' => [
                'ar' => 'اختر خطة الاشتراك',
                'en' => 'Select Subscription Plan'
            ],
            'subscription_details' => [
                'ar' => 'تفاصيل الاشتراك',
                'en' => 'Subscription Details'
            ],
            'price' => [
                'ar' => 'السعر',
                'en' => 'Price'
            ],
            'payment_required' => [
                'ar' => 'الدفع مطلوب',
                'en' => 'Payment Required'
            ],
            'no_payment_required' => [
                'ar' => 'لا يتطلب الدفع',
                'en' => 'No Payment Required'
            ],
            'select' => [
                'ar' => 'اختر',
                'en' => 'Select'
            ],
            'error_fetching_plans' => [
                'ar' => 'حدث خطأ أثناء جلب الخطط',
                'en' => 'Error fetching plans'
            ],
            'no_plans_available_for_circle' => [
                'ar' => 'لا توجد خطط متاحة لهذه الحلقة',
                'en' => 'No plans available for this circle'
            ],
            // Payment related translations
            'subscription_payment' => [
                'ar' => 'دفع الاشتراك',
                'en' => 'Subscription Payment'
            ],
            'back_to_subscription' => [
                'ar' => 'العودة إلى الاشتراك',
                'en' => 'Back to Subscription'
            ],
            'subscription_details' => [
                'ar' => 'تفاصيل الاشتراك',
                'en' => 'Subscription Details'
            ],
            'payment_methods' => [
                'ar' => 'طرق الدفع',
                'en' => 'Payment Methods'
            ],
            'credit_debit_card' => [
                'ar' => 'بطاقة ائتمان/خصم',
                'en' => 'Credit/Debit Card'
            ],
            'bank_transfer' => [
                'ar' => 'تحويل بنكي',
                'en' => 'Bank Transfer'
            ],
            'payment_gateway_redirect_notice' => [
                'ar' => 'سيتم تحويلك إلى بوابة الدفع الآمنة لإتمام عملية الدفع',
                'en' => 'You will be redirected to the secure payment gateway to complete your payment'
            ],
            'secure_payment' => [
                'ar' => 'دفع آمن',
                'en' => 'Secure Payment'
            ],
            'demo_only' => [
                'ar' => 'للعرض التوضيحي فقط',
                'en' => 'Demo Only'
            ],
            'simulate_successful_payment' => [
                'ar' => 'محاكاة دفع ناجح',
                'en' => 'Simulate Successful Payment'
            ],
            'payment_processed_successfully' => [
                'ar' => 'تم معالجة الدفع بنجاح',
                'en' => 'Payment Processed Successfully'
            ],
            'error_processing_payment' => [
                'ar' => 'خطأ في معالجة الدفع',
                'en' => 'Error Processing Payment'
            ],
            'payment_system_disabled' => [
                'ar' => 'نظام الدفع معطل',
                'en' => 'Payment System Disabled'
            ],
            'no_pending_transactions' => [
                'ar' => 'لا توجد معاملات معلقة',
                'en' => 'No Pending Transactions'
            ],
            'payment_information' => [
                'ar' => 'معلومات الدفع',
                'en' => 'Payment Information'
            ],
            'no_payment_transactions_found' => [
                'ar' => 'لم يتم العثور على معاملات دفع',
                'en' => 'No Payment Transactions Found'
            ],
            'transaction_id' => [
                'ar' => 'رقم العملية',
                'en' => 'Transaction ID'
            ],
            'method' => [
                'ar' => 'الطريقة',
                'en' => 'Method'
            ],
            'status' => [
                'ar' => 'الحالة',
                'en' => 'Status'
            ],
            'date' => [
                'ar' => 'التاريخ',
                'en' => 'Date'
            ],
            'subscription_created_proceed_to_payment' => [
                'ar' => 'تم إنشاء الاشتراك بنجاح. يرجى المتابعة للدفع',
                'en' => 'Subscription created successfully. Please proceed to payment'
            ],
            'subscription_created_successfully' => [
                'ar' => 'تم إنشاء الاشتراك بنجاح',
                'en' => 'Subscription Created Successfully'
            ],
            'error_creating_subscription' => [
                'ar' => 'خطأ في إنشاء الاشتراك',
                'en' => 'Error Creating Subscription'
            ],
            'only_pending_subscriptions_can_be_cancelled' => [
                'ar' => 'يمكن إلغاء الاشتراكات المعلقة فقط',
                'en' => 'Only Pending Subscriptions Can Be Cancelled'
            ],
            'subscription_cancelled_successfully' => [
                'ar' => 'تم إلغاء الاشتراك بنجاح',
                'en' => 'Subscription Cancelled Successfully'
            ],
            'error_cancelling_subscription' => [
                'ar' => 'خطأ في إلغاء الاشتراك',
                'en' => 'Error Cancelling Subscription'
            ],
            'no_active_subscription_found_create_new' => [
                'ar' => 'لم يتم العثور على اشتراك نشط. يمكنك إنشاء اشتراك جديد',
                'en' => 'No Active Subscription Found. Create New'
            ]
        ];
    }
} 