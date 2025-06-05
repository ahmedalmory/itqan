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
            'unauthorized_action' => [
                'ar' => 'غير مصرح بهذا الإجراء',
                'en' => 'Unauthorized action'
            ],
            'student_not_in_circle' => [
                'ar' => 'الطالب غير مسجل في هذه الحلقة',
                'en' => 'Student not in this circle'
            ],
        ];
    }
} 