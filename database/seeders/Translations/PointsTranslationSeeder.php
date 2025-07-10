<?php

namespace Database\Seeders\Translations;

class PointsTranslationSeeder extends AbstractTranslationSeeder
{
    protected function getTranslations(): array
    {
        return [
            // Titles and Headers
            'Points Management' => [
                'en' => 'Points Management',
                'ar' => 'إدارة النقاط',
            ],
            'Points Leaderboard' => [
                'en' => 'Points Leaderboard',
                'ar' => 'لوحة صدارة النقاط',
            ],
            'Back to Points' => [
                'en' => 'Back to Points',
                'ar' => 'العودة إلى النقاط',
            ],
            'View Leaderboard' => [
                'en' => 'View Leaderboard',
                'ar' => 'عرض لوحة الصدارة',
            ],
            'Share as Image' => [
                'en' => 'Share as Image',
                'ar' => 'مشاركة كصورة',
            ],
            'Bulk Assign Points' => [
                'en' => 'Bulk Assign Points',
                'ar' => 'إضافة نقاط للجميع',
            ],
            'Bulk Assign Points for Circle' => [
                'en' => 'Bulk Assign Points for Circle',
                'ar' => 'إضافة نقاط للحلقة',
            ],
            'Manage Points for' => [
                'en' => 'Manage Points for',
                'ar' => 'إدارة نقاط',
            ],
            'Points History for' => [
                'en' => 'Points History for',
                'ar' => 'سجل نقاط',
            ],

            // Actions and Buttons
            'Add Points' => [
                'en' => 'Add Points',
                'ar' => 'إضافة نقاط',
            ],
            'History' => [
                'en' => 'History',
                'ar' => 'السجل',
            ],
            'Quick Assign' => [
                'en' => 'Quick Assign',
                'ar' => 'إضافة سريعة',
            ],
            'Points for All' => [
                'en' => 'Points for All',
                'ar' => 'نقاط للجميع',
            ],
            'Apply to All' => [
                'en' => 'Apply to All',
                'ar' => 'تطبيق على الجميع',
            ],
            'Save Changes' => [
                'en' => 'Save Changes',
                'ar' => 'حفظ التغييرات',
            ],
            'Save' => [
                'en' => 'Save',
                'ar' => 'حفظ',
            ],
            'Cancel' => [
                'en' => 'Cancel',
                'ar' => 'إلغاء',
            ],
            'Close' => [
                'en' => 'Close',
                'ar' => 'إغلاق',
            ],

            // Table Headers
            'Rank' => [
                'en' => 'Rank',
                'ar' => 'المرتبة',
            ],
            'Student' => [
                'en' => 'Student',
                'ar' => 'الطالب',
            ],
            'Student Name' => [
                'en' => 'Student Name',
                'ar' => 'اسم الطالب',
            ],
            'Total Points' => [
                'en' => 'Total Points',
                'ar' => 'مجموع النقاط',
            ],
            'Points to Add' => [
                'en' => 'Points to Add',
                'ar' => 'النقاط المراد إضافتها',
            ],
            'Actions' => [
                'en' => 'Actions',
                'ar' => 'الإجراءات',
            ],
            'Circle' => [
                'en' => 'Circle',
                'ar' => 'الحلقة',
            ],
            'Date' => [
                'en' => 'Date',
                'ar' => 'التاريخ',
            ],
            'Action' => [
                'en' => 'Action',
                'ar' => 'الإجراء',
            ],
            'Notes' => [
                'en' => 'Notes',
                'ar' => 'الملاحظات',
            ],
            'Added By' => [
                'en' => 'Added By',
                'ar' => 'أضيف بواسطة',
            ],

            // Form Labels
            'Points' => [
                'en' => 'Points',
                'ar' => 'النقاط',
            ],
            'Reason' => [
                'en' => 'Reason',
                'ar' => 'السبب',
            ],
            'Select Circle' => [
                'en' => 'Select Circle',
                'ar' => 'اختر الحلقة',
            ],
            'All Circles' => [
                'en' => 'All Circles',
                'ar' => 'جميع الحلقات',
            ],
            'All Departments' => [
                'en' => 'All Departments',
                'ar' => 'جميع الأقسام',
            ],

            // Messages
            'No students found' => [
                'en' => 'No students found',
                'ar' => 'لم يتم العثور على طلاب',
            ],
            'Please select a circle to manage points' => [
                'en' => 'Please select a circle to manage points',
                'ar' => 'الرجاء اختيار حلقة لإدارة النقاط',
            ],
            'No students found in this circle' => [
                'en' => 'No students found in this circle',
                'ar' => 'لا يوجد طلاب في هذه الحلقة',
            ],
            'Use negative values to subtract points' => [
                'en' => 'Use negative values to subtract points',
                'ar' => 'استخدم القيم السالبة لخصم النقاط',
            ],
            'No points history found' => [
                'en' => 'No points history found',
                'ar' => 'لم يتم العثور على سجل نقاط',
            ],
            'students' => [
                'en' => 'students',
                'ar' => 'طلاب',
            ],
            'N/A' => [
                'en' => 'N/A',
                'ar' => 'غير متوفر',
            ],

            // Positions
            'First' => [
                'en' => 'First',
                'ar' => 'الأول',
            ],
            'Second' => [
                'en' => 'Second',
                'ar' => 'الثاني',
            ],
            'Third' => [
                'en' => 'Third',
                'ar' => 'الثالث',
            ],

            // Badges and Labels
            'Champion' => [
                'en' => 'Champion',
                'ar' => 'البطل',
            ],
            'Runner Up' => [
                'en' => 'Runner Up',
                'ar' => 'الوصيف',
            ],
            'Bronze Winner' => [
                'en' => 'Bronze Winner',
                'ar' => 'الفائز بالبرونز',
            ],

            // Action Types
            'add' => [
                'en' => 'Add',
                'ar' => 'إضافة',
            ],
            'subtract' => [
                'en' => 'Subtract',
                'ar' => 'خصم',
            ],

            // Points Summary
            'points_summary' => [
                'en' => 'Points Summary',
                'ar' => 'ملخص النقاط',
            ],
            'total_history_records' => [
                'en' => 'Total History Records',
                'ar' => 'إجمالي سجلات التاريخ',
            ],
            'points_breakdown' => [
                'en' => 'Points Breakdown',
                'ar' => 'تفصيل النقاط',
            ],
            'records_count' => [
                'en' => 'Records Count',
                'ar' => 'عدد السجلات',
            ],
            'last_activity' => [
                'en' => 'Last Activity',
                'ar' => 'آخر نشاط',
            ],
            'total_points' => [
                'en' => 'Total Points',
                'ar' => 'إجمالي النقاط',
            ],

            // Success Messages
            'points_updated_successfully' => [
                'en' => 'Points updated successfully',
                'ar' => 'تم تحديث النقاط بنجاح',
            ],

            // Error Messages
            'error_updating_points' => [
                'en' => 'Error updating points',
                'ar' => 'خطأ في تحديث النقاط',
            ],
            'student_not_in_circle' => [
                'en' => 'Student is not in the selected circle',
                'ar' => 'الطالب ليس في الحلقة المختارة',
            ],
            'unauthorized_action' => [
                'en' => 'Unauthorized action',
                'ar' => 'إجراء غير مصرح به',
            ],
            'Filter' => [
                'en' => 'Filter',
                'ar' => 'فلتر',
            ],
            'from_date' => [
                'en' => 'From Date',
                'ar' => 'من تاريخ',
            ],
            'to_date' => [
                'en' => 'To Date',
                'ar' => 'إلى تاريخ',
            ],
        ];
    }
} 