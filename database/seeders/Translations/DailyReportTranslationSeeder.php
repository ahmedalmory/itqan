<?php

namespace Database\Seeders\Translations;

class DailyReportTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     * 
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // Daily Reports Main Sections
            'daily_reports' => [
                'ar' => 'التقارير اليومية',
                'en' => 'Daily Reports'
            ],
            'manage_daily_reports' => [
                'ar' => 'إدارة التقارير اليومية',
                'en' => 'Manage Daily Reports'
            ],
            'view_history' => [
                'ar' => 'عرض السجل',
                'en' => 'View History'
            ],
            'bulk_add_reports' => [
                'ar' => 'إضافة تقارير متعددة',
                'en' => 'Bulk Add Reports'
            ],
            'add_all_students' => [
                'ar' => 'إضافة جميع الطلاب',
                'en' => 'Add All Students'
            ],
            'select_circle_to_manage_reports' => [
                'ar' => 'اختر الحلقة لإدارة التقارير',
                'en' => 'Select Circle to Manage Reports'
            ],
            'no_students_in_selected_circle' => [
                'ar' => 'لا يوجد طلاب في الحلقة المختارة',
                'en' => 'No Students in Selected Circle'
            ],

            // Report Status
            'report_submitted' => [
                'ar' => 'تم تقديم التقرير',
                'en' => 'Report Submitted'
            ],
            'no_report' => [
                'ar' => 'لا يوجد تقرير',
                'en' => 'No Report'
            ],
            'no_report_details' => [
                'ar' => 'لا توجد تفاصيل للتقرير',
                'en' => 'No Report Details'
            ],

            // Common Values Section
            'common_values' => [
                'ar' => 'القيم المشتركة',
                'en' => 'Common Values'
            ],
            'apply_to_all_students' => [
                'ar' => 'تطبيق على جميع الطلاب',
                'en' => 'Apply to All Students'
            ],
            'add_another_student' => [
                'ar' => 'إضافة طالب آخر',
                'en' => 'Add Another Student'
            ],

            // Report Fields
            'memorization_parts' => [
                'ar' => 'أوجه الحفظ',
                'en' => 'Memorization Parts'
            ],
            'revision_parts' => [
                'ar' => 'أوجه المراجعة',
                'en' => 'Revision Parts'
            ],
            'from_surah' => [
                'ar' => 'من سورة',
                'en' => 'From Surah'
            ],
            'to_surah' => [
                'ar' => 'إلى سورة',
                'en' => 'To Surah'
            ],
            'from_verse' => [
                'ar' => 'من آية',
                'en' => 'From Verse'
            ],
            'to_verse' => [
                'ar' => 'إلى آية',
                'en' => 'To Verse'
            ],
            'grade' => [
                'ar' => 'الدرجة',
                'en' => 'Grade'
            ],
            'notes' => [
                'ar' => 'الملاحظات',
                'en' => 'Notes'
            ],
            'report_date' => [
                'ar' => 'تاريخ التقرير',
                'en' => 'Report Date'
            ],
            'select_student' => [
                'ar' => 'اختر الطالب',
                'en' => 'Select Student'
            ],
            'select_surah' => [
                'ar' => 'اختر السورة',
                'en' => 'Select Surah'
            ],

            // Actions
            'save_reports' => [
                'ar' => 'حفظ التقارير',
                'en' => 'Save Reports'
            ],
            'save_report' => [
                'ar' => 'حفظ التقرير',
                'en' => 'Save Report'
            ],
            'confirm_delete_report' => [
                'ar' => 'هل أنت متأكد من حذف هذا التقرير؟',
                'en' => 'Are you sure you want to delete this report?'
            ],
            'daily_report_for' => [
                'ar' => 'التقرير اليومي لـ',
                'en' => 'Daily Report for'
            ],
            'bulk_add_reports_for_circle' => [
                'ar' => 'إضافة تقارير متعددة للحلقة: :circle',
                'en' => 'Bulk Add Reports for Circle: :circle'
            ],

            // Success Messages
            'reports_created_successfully' => [
                'ar' => 'تم إنشاء التقارير بنجاح',
                'en' => 'Reports created successfully'
            ],
            'report_created_successfully' => [
                'ar' => 'تم إنشاء التقرير بنجاح',
                'en' => 'Report created successfully'
            ],
            'report_updated_successfully' => [
                'ar' => 'تم تحديث التقرير بنجاح',
                'en' => 'Report updated successfully'
            ],
            'report_deleted_successfully' => [
                'ar' => 'تم حذف التقرير بنجاح',
                'en' => 'Report deleted successfully'
            ],

            // Error Messages
            'error_creating_reports' => [
                'ar' => 'حدث خطأ أثناء إنشاء التقارير',
                'en' => 'Error creating reports'
            ],
            'optional' => [
                'ar' => 'اختياري',
                'en' => 'Optional'
            ],
            'remove_row' => [
                'ar' => 'حذف الصف',
                'en' => 'Remove Row'
            ],
            'day' => [
                'ar' => 'يوم',
                'en' => 'Day'
            ],
            'week' => [
                'ar' => 'أسبوع',
                'en' => 'Week'
            ],
            'month' => [
                'ar' => 'شهر',
                'en' => 'Month'
            ],
            'monday' => [
                'ar' => 'الإثنين',
                'en' => 'Monday'
            ],
            'tuesday' => [
                'ar' => 'الثلاثاء',
                'en' => 'Tuesday'
            ],
            'wednesday' => [
                'ar' => 'الأربعاء',
                'en' => 'Wednesday'
            ],
            'thursday' => [
                'ar' => 'الخميس',
                'en' => 'Thursday'
            ],
            'friday' => [
                'ar' => 'الجمعة',
                'en' => 'Friday'
            ],
            'saturday' => [
                'ar' => 'السبت',
                'en' => 'Saturday'
            ],
            'sunday' => [
                'ar' => 'الأحد',
                'en' => 'Sunday'
            ],
            'student_id' => [
                'ar' => 'رقم الطالب',
                'en' => 'Student ID'
            ],
            'avg_grade' => [
                'ar' => 'متوسط الدرجة',
                'en' => 'Avg Grade'
            ],
            'unauthorized_action' => [
                'ar' => 'غير مصرح بهذا الإجراء',
                'en' => 'Unauthorized action'
            ],
            'student_not_in_circle' => [
                'ar' => 'الطالب غير مسجل في هذه الحلقة',
                'en' => 'Student not in this circle'
            ],

            // Calendar View Translations
            'daily_reports_calendar' => [
                'ar' => 'تقويم التقارير اليومية',
                'en' => 'Daily Reports Calendar'
            ],
            'calendar_view' => [
                'ar' => 'عرض التقويم',
                'en' => 'Calendar View'
            ],
            'table_view' => [
                'ar' => 'عرض الجدول',
                'en' => 'Table View'
            ],
            'select_circle_to_view_calendar' => [
                'ar' => 'اختر الحلقة لعرض التقويم',
                'en' => 'Select Circle to View Calendar'
            ],
            'total_students' => [
                'ar' => 'إجمالي الطلاب',
                'en' => 'Total Students'
            ],
            'attendance_rate' => [
                'ar' => 'معدل الحضور',
                'en' => 'Attendance Rate'
            ],
            'total_memorization' => [
                'ar' => 'إجمالي الحفظ',
                'en' => 'Total Memorization'
            ],
            'total_revision' => [
                'ar' => 'إجمالي المراجعة',
                'en' => 'Total Revision'
            ],
            'average_grade' => [
                'ar' => 'متوسط الدرجات',
                'en' => 'Average Grade'
            ],
            'total_reports' => [
                'ar' => 'إجمالي التقارير',
                'en' => 'Total Reports'
            ],
            'legend' => [
                'ar' => 'وصف الألوان',
                'en' => 'Legend'
            ],
            'memorization_and_revision' => [
                'ar' => 'حفظ ومراجعة',
                'en' => 'Memorization and Revision'
            ],
            'memorization_only' => [
                'ar' => 'حفظ فقط',
                'en' => 'Memorization Only'
            ],
            'revision_only' => [
                'ar' => 'مراجعة فقط',
                'en' => 'Revision Only'
            ],
            'no_report' => [
                'ar' => 'لا يوجد تقرير',
                'en' => 'No Report'
            ],
            'future_date' => [
                'ar' => 'تاريخ مستقبلي',
                'en' => 'Future Date'
            ],
            'joined' => [
                'ar' => 'انضم',
                'en' => 'Joined'
            ],
            'report_details' => [
                'ar' => 'تفاصيل التقرير',
                'en' => 'Report Details'
            ],
            'memorization_details' => [
                'ar' => 'تفاصيل الحفظ',
                'en' => 'Memorization Details'
            ],
            'revision_details' => [
                'ar' => 'تفاصيل المراجعة',
                'en' => 'Revision Details'
            ],
            'parts' => [
                'ar' => 'الأوجه',
                'en' => 'Parts'
            ],
            'verses' => [
                'ar' => 'الآيات',
                'en' => 'Verses'
            ],
            'error_loading_report_details' => [
                'ar' => 'خطأ في تحميل تفاصيل التقرير',
                'en' => 'Error loading report details'
            ],
            'no_memorization' => [
                'ar' => 'لا يوجد حفظ',
                'en' => 'No memorization'
            ],
            'no_revision' => [
                'ar' => 'لا توجد مراجعة',
                'en' => 'No revision'
            ],
            'revision' => [
                'ar' => 'المراجعة',
                'en' => 'Revision'
            ],
            'back_to_calendar' => [
                'ar' => 'العودة إلى التقويم',
                'en' => 'Back to Calendar'
            ],
            'bulk_add_reports' => [
                'ar' => 'إضافة تقارير مجمعة',
                'en' => 'Bulk Add Reports'
            ],
            'bulk_add_reports_for_circle' => [
                'ar' => 'إضافة تقارير مجمعة للحلقة: :circle',
                'en' => 'Bulk Add Reports for Circle: :circle'
            ],
            'common_values' => [
                'ar' => 'القيم المشتركة',
                'en' => 'Common Values'
            ],
            'apply_to_all_students' => [
                'ar' => 'تطبيق على جميع الطلاب',
                'en' => 'Apply to All Students'
            ],
            'add_another_student' => [
                'ar' => 'إضافة طالب آخر',
                'en' => 'Add Another Student'
            ],
            'add_all_students' => [
                'ar' => 'إضافة جميع الطلاب',
                'en' => 'Add All Students'
            ],
            'save_reports' => [
                'ar' => 'حفظ التقارير',
                'en' => 'Save Reports'
            ],
            'select_student' => [
                'ar' => 'اختر طالب',
                'en' => 'Select Student'
            ],
            'select_surah' => [
                'ar' => 'اختر سورة',
                'en' => 'Select Surah'
            ],
            'reports_created_successfully' => [
                'ar' => 'تم إنشاء التقارير بنجاح',
                'en' => 'Reports created successfully'
            ],
            'error_creating_reports' => [
                'ar' => 'خطأ في إنشاء التقارير',
                'en' => 'Error creating reports'
            ],
            'view_options' => [
                'ar' => 'خيارات العرض',
                'en' => 'View Options'
            ],
            'mem' => [
                'ar' => 'حفظ',
                'en' => 'Mem'
            ],
            'rev' => [
                'ar' => 'مراجعة',
                'en' => 'Rev'
            ],
            'no_report_tooltip' => [
                'ar' => 'لا يوجد تقرير',
                'en' => 'No report'
            ],
        ];
    }
} 