<?php

namespace Database\Seeders\Translations;

class TeacherTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     *
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // Dashboard
            'teacher_dashboard' => [
                'en' => 'Teacher Dashboard',
                'ar' => 'لوحة تحكم المعلم'
            ],
            'my_circles' => [
                'en' => 'My Circles',
                'ar' => 'حلقاتي'
            ],
            'total_students' => [
                'en' => 'Total Students',
                'ar' => 'إجمالي الطلاب'
            ],
            'todays_reports' => [
                'en' => 'Today\'s Reports',
                'ar' => 'تقارير اليوم'
            ],
            'attendance_rate' => [
                'en' => 'Attendance Rate',
                'ar' => 'معدل الحضور'
            ],
            'todays_attendance' => [
                'en' => 'Today\'s Attendance',
                'ar' => 'حضور اليوم'
            ],
            'add_reports' => [
                'en' => 'Add Reports',
                'ar' => 'إضافة تقارير'
            ],
            'circle' => [
                'en' => 'Circle',
                'ar' => 'الحلقة'
            ],
            'reports_submitted' => [
                'en' => 'Reports Submitted',
                'ar' => 'التقارير المقدمة'
            ],
            'progress' => [
                'en' => 'Progress',
                'ar' => 'التقدم'
            ],
            'action' => [
                'en' => 'Action',
                'ar' => 'إجراء'
            ],
            'recent_reports' => [
                'en' => 'Recent Reports',
                'ar' => 'التقارير الأخيرة'
            ],
            'date' => [
                'en' => 'Date',
                'ar' => 'التاريخ'
            ],
            'student' => [
                'en' => 'Student',
                'ar' => 'الطالب'
            ],
            'memorization' => [
                'en' => 'Memorization',
                'ar' => 'الحفظ'
            ],
            'grade' => [
                'en' => 'Grade',
                'ar' => 'الدرجة'
            ],
            'department' => [
                'en' => 'Department',
                'ar' => 'القسم'
            ],
            'students' => [
                'en' => 'Students',
                'ar' => 'الطلاب'
            ],
            'quick_actions' => [
                'en' => 'Quick Actions',
                'ar' => 'إجراءات سريعة'
            ],
            'add_daily_reports' => [
                'en' => 'Add Daily Reports',
                'ar' => 'إضافة تقارير يومية'
            ],
            'manage_circles' => [
                'en' => 'Manage Circles',
                'ar' => 'إدارة الحلقات'
            ],
            'manage_points' => [
                'en' => 'Manage Points',
                'ar' => 'إدارة النقاط'
            ],
            'pages' => [
                'en' => 'Pages',
                'ar' => 'صفحات'
            ],
            
            // Daily Reports
            'daily_reports' => [
                'en' => 'Daily Reports',
                'ar' => 'التقارير اليومية'
            ],
            'manage_daily_reports' => [
                'en' => 'Manage Daily Reports',
                'ar' => 'إدارة التقارير اليومية'
            ],
            'view_history' => [
                'en' => 'View History',
                'ar' => 'عرض السجل'
            ],
            'select' => [
                'en' => 'Select',
                'ar' => 'اختيار'
            ],
            'filter' => [
                'en' => 'Filter',
                'ar' => 'تصفية'
            ],
            'select_circle_to_manage_reports' => [
                'en' => 'Please select a circle to manage reports',
                'ar' => 'الرجاء اختيار حلقة لإدارة التقارير'
            ],
            'no_students_in_selected_circle' => [
                'en' => 'No students found in the selected circle',
                'ar' => 'لا يوجد طلاب في الحلقة المختارة'
            ],
            'status' => [
                'en' => 'Status',
                'ar' => 'الحالة'
            ],
            'report_details' => [
                'en' => 'Report Details',
                'ar' => 'تفاصيل التقرير'
            ],
            'actions' => [
                'en' => 'Actions',
                'ar' => 'إجراءات'
            ],
            'report_submitted' => [
                'en' => 'Report Submitted',
                'ar' => 'تم تقديم التقرير'
            ],
            'no_report' => [
                'en' => 'No Report',
                'ar' => 'لا يوجد تقرير'
            ],
            'no_report_details' => [
                'en' => 'No report details available',
                'ar' => 'لا توجد تفاصيل للتقرير'
            ],
            'edit' => [
                'en' => 'Edit',
                'ar' => 'تعديل'
            ],
            'add' => [
                'en' => 'Add',
                'ar' => 'إضافة'
            ],
            'delete' => [
                'en' => 'Delete',
                'ar' => 'حذف'
            ],
            'confirm_delete_report' => [
                'en' => 'Are you sure you want to delete this report?',
                'ar' => 'هل أنت متأكد أنك تريد حذف هذا التقرير؟'
            ],
            'daily_report_for' => [
                'en' => 'Daily Report for',
                'ar' => 'التقرير اليومي لـ'
            ],
            'from_surah' => [
                'en' => 'From Surah',
                'ar' => 'من سورة'
            ],
            'from_verse' => [
                'en' => 'From Verse',
                'ar' => 'من آية'
            ],
            'to_surah' => [
                'en' => 'To Surah',
                'ar' => 'إلى سورة'
            ],
            'to_verse' => [
                'en' => 'To Verse',
                'ar' => 'إلى آية'
            ],
            'memorization_parts' => [
                'en' => 'Memorization Parts',
                'ar' => 'أجزاء الحفظ'
            ],
            'notes' => [
                'en' => 'Notes',
                'ar' => 'ملاحظات'
            ],
            'cancel' => [
                'en' => 'Cancel',
                'ar' => 'إلغاء'
            ],
            'save_report' => [
                'en' => 'Save Report',
                'ar' => 'حفظ التقرير'
            ],
            
            // Reports History
            'reports_history' => [
                'en' => 'Reports History',
                'ar' => 'سجل التقارير'
            ],
            'daily_reports_history' => [
                'en' => 'Daily Reports History',
                'ar' => 'سجل التقارير اليومية'
            ],
            'all_circles' => [
                'en' => 'All Circles',
                'ar' => 'جميع الحلقات'
            ],
            'all_students' => [
                'en' => 'All Students',
                'ar' => 'جميع الطلاب'
            ],
            'from_date' => [
                'en' => 'From Date',
                'ar' => 'من تاريخ'
            ],
            'to_date' => [
                'en' => 'To Date',
                'ar' => 'إلى تاريخ'
            ],
            'clear_filters' => [
                'en' => 'Clear Filters',
                'ar' => 'مسح التصفية'
            ],
            'no_reports_found' => [
                'en' => 'No reports found',
                'ar' => 'لم يتم العثور على تقارير'
            ],
            'unknown_circle' => [
                'en' => 'Unknown Circle',
                'ar' => 'حلقة غير معروفة'
            ],
            
            // Points Management
            'points_management' => [
                'en' => 'Points Management',
                'ar' => 'إدارة النقاط'
            ],
            'leaderboard' => [
                'en' => 'Leaderboard',
                'ar' => 'لوحة المتصدرين'
            ],
            'select_circle_to_manage_points' => [
                'en' => 'Please select a circle to manage points',
                'ar' => 'الرجاء اختيار حلقة لإدارة النقاط'
            ],
            'current_points' => [
                'en' => 'Current Points',
                'ar' => 'النقاط الحالية'
            ],
            'total_points' => [
                'en' => 'Total Points',
                'ar' => 'إجمالي النقاط'
            ],
            'add_points' => [
                'en' => 'Add Points',
                'ar' => 'إضافة نقاط'
            ],
            'history' => [
                'en' => 'History',
                'ar' => 'السجل'
            ],
            'manage_points_for' => [
                'en' => 'Manage Points for',
                'ar' => 'إدارة نقاط لـ'
            ],
            'points' => [
                'en' => 'Points',
                'ar' => 'نقاط'
            ],
            'points_help_text' => [
                'en' => 'Enter positive value to add points, negative value to deduct points',
                'ar' => 'أدخل قيمة موجبة لإضافة نقاط، قيمة سالبة لخصم نقاط'
            ],
            'reason' => [
                'en' => 'Reason',
                'ar' => 'السبب'
            ],
            'select_reason' => [
                'en' => 'Select a reason',
                'ar' => 'اختر سببًا'
            ],
            'daily_memorization' => [
                'en' => 'Daily Memorization',
                'ar' => 'الحفظ اليومي'
            ],
            'participation' => [
                'en' => 'Participation',
                'ar' => 'المشاركة'
            ],
            'good_behavior' => [
                'en' => 'Good Behavior',
                'ar' => 'السلوك الجيد'
            ],
            'extra_activities' => [
                'en' => 'Extra Activities',
                'ar' => 'أنشطة إضافية'
            ],
            'absence' => [
                'en' => 'Absence',
                'ar' => 'الغياب'
            ],
            'misconduct' => [
                'en' => 'Misconduct',
                'ar' => 'سوء السلوك'
            ],
            'other' => [
                'en' => 'Other',
                'ar' => 'أخرى'
            ],
            'save' => [
                'en' => 'Save',
                'ar' => 'حفظ'
            ],
            
            // Points History
            'points_history' => [
                'en' => 'Points History',
                'ar' => 'سجل النقاط'
            ],
            'points_history_for' => [
                'en' => 'Points History for',
                'ar' => 'سجل النقاط لـ'
            ],
            'back_to_points' => [
                'en' => 'Back to Points',
                'ar' => 'العودة إلى النقاط'
            ],
            'points_summary' => [
                'en' => 'Points Summary',
                'ar' => 'ملخص النقاط'
            ],
            'current' => [
                'en' => 'current',
                'ar' => 'حالي'
            ],
            'total' => [
                'en' => 'total',
                'ar' => 'إجمالي'
            ],
            'no_points_history_found' => [
                'en' => 'No points history found',
                'ar' => 'لم يتم العثور على سجل نقاط'
            ],
            'awarded_by' => [
                'en' => 'Awarded By',
                'ar' => 'تم منحها بواسطة'
            ],
            'unknown' => [
                'en' => 'Unknown',
                'ar' => 'غير معروف'
            ],
            
            // Points Leaderboard
            'points_leaderboard' => [
                'en' => 'Points Leaderboard',
                'ar' => 'لوحة متصدري النقاط'
            ],
            'filter_by_circle' => [
                'en' => 'Filter by Circle',
                'ar' => 'تصفية حسب الحلقة'
            ],
            'no_students_with_points' => [
                'en' => 'No students with points found',
                'ar' => 'لم يتم العثور على طلاب لديهم نقاط'
            ],
            
            // Circle Views
            'circle_details' => [
                'en' => 'Circle Details',
                'ar' => 'تفاصيل الحلقة'
            ],
            'back_to_circles' => [
                'en' => 'Back to Circles',
                'ar' => 'العودة إلى الحلقات'
            ],
            'back_to_circle' => [
                'en' => 'Back to Circle',
                'ar' => 'العودة إلى الحلقة'
            ],
            'back_to_dashboard' => [
                'en' => 'Back to Dashboard',
                'ar' => 'العودة إلى لوحة التحكم'
            ],
            'no_circles_found' => [
                'en' => 'No circles found',
                'ar' => 'لم يتم العثور على حلقات'
            ],
            'circle_time' => [
                'en' => 'Circle Time',
                'ar' => 'وقت الحلقة'
            ],
            'description' => [
                'en' => 'Description',
                'ar' => 'الوصف'
            ],
            'view' => [
                'en' => 'View',
                'ar' => 'عرض'
            ],
            'whatsapp_group' => [
                'en' => 'WhatsApp Group',
                'ar' => 'مجموعة واتساب'
            ],
            'telegram_group' => [
                'en' => 'Telegram Group',
                'ar' => 'مجموعة تيليجرام'
            ],
            'max_students' => [
                'en' => 'Maximum Students',
                'ar' => 'الحد الأقصى للطلاب'
            ],
            'meeting_days' => [
                'en' => 'Meeting Days',
                'ar' => 'أيام الاجتماع'
            ],
            'meeting_time' => [
                'en' => 'Meeting Time',
                'ar' => 'وقت الاجتماع'
            ],
            'meeting_link' => [
                'en' => 'Meeting Link',
                'ar' => 'رابط الاجتماع'
            ],
            'not_specified' => [
                'en' => 'Not Specified',
                'ar' => 'غير محدد'
            ],
            'not_available' => [
                'en' => 'Not Available',
                'ar' => 'غير متاح'
            ],
            'join_meeting' => [
                'en' => 'Join Meeting',
                'ar' => 'انضم إلى الاجتماع'
            ],
            'communication_channels' => [
                'en' => 'Communication Channels',
                'ar' => 'قنوات التواصل'
            ],
            'no_communication_channels' => [
                'en' => 'No communication channels available',
                'ar' => 'لا توجد قنوات تواصل متاحة'
            ],
            'circle_statistics' => [
                'en' => 'Circle Statistics',
                'ar' => 'إحصاءات الحلقة'
            ],
            'enrolled_students' => [
                'en' => 'Enrolled Students',
                'ar' => 'الطلاب المسجلين'
            ],
            'available_slots' => [
                'en' => 'Available Slots',
                'ar' => 'المقاعد المتاحة'
            ],
            'enrollment_status' => [
                'en' => 'Enrollment Status',
                'ar' => 'حالة التسجيل'
            ],
            'manage_students' => [
                'en' => 'Manage Students',
                'ar' => 'إدارة الطلاب'
            ],
            'circle_supervisor' => [
                'en' => 'Circle Supervisor',
                'ar' => 'مشرف الحلقة'
            ],
            'no_supervisor_assigned' => [
                'en' => 'No supervisor assigned',
                'ar' => 'لم يتم تعيين مشرف'
            ],
            'students_list' => [
                'en' => 'Students List',
                'ar' => 'قائمة الطلاب'
            ],
            'no_students_in_this_circle' => [
                'en' => 'No students in this circle',
                'ar' => 'لا يوجد طلاب في هذه الحلقة'
            ],
            'email' => [
                'en' => 'Email',
                'ar' => 'البريد الإلكتروني'
            ],
            'joined_date' => [
                'en' => 'Joined Date',
                'ar' => 'تاريخ الانضمام'
            ],
            'no_reports' => [
                'en' => 'No Reports',
                'ar' => 'لا توجد تقارير'
            ],
            'last' => [
                'en' => 'Last',
                'ar' => 'آخر'
            ],
            'reports' => [
                'en' => 'Reports',
                'ar' => 'التقارير'
            ],
            'edit_circle' => [
                'en' => 'Edit Circle',
                'ar' => 'تعديل الحلقة'
            ],
            'edit_circle_details' => [
                'en' => 'Edit Circle Details',
                'ar' => 'تعديل تفاصيل الحلقة'
            ],
            'circle_name' => [
                'en' => 'Circle Name',
                'ar' => 'اسم الحلقة'
            ],
            'circle_name_managed_by_admin' => [
                'en' => 'Circle name can only be changed by administrators',
                'ar' => 'يمكن تغيير اسم الحلقة فقط بواسطة المسؤولين'
            ],
            'meeting_days_help' => [
                'en' => 'E.g., Sunday, Tuesday, Thursday',
                'ar' => 'مثال: الأحد، الثلاثاء، الخميس'
            ],
            'meeting_time_help' => [
                'en' => 'E.g., 7:00 PM - 9:00 PM',
                'ar' => 'مثال: 7:00 مساءً - 9:00 مساءً'
            ],
            'meeting_link_help' => [
                'en' => 'Link to virtual meeting (Zoom, Google Meet, etc.)',
                'ar' => 'رابط الاجتماع الافتراضي (زوم، جوجل ميت، إلخ)'
            ],
            'whatsapp_group_link' => [
                'en' => 'WhatsApp Group Link',
                'ar' => 'رابط مجموعة واتساب'
            ],
            'telegram_group_link' => [
                'en' => 'Telegram Group Link',
                'ar' => 'رابط مجموعة تيليجرام'
            ],
            'update_circle' => [
                'en' => 'Update Circle',
                'ar' => 'تحديث الحلقة'
            ],
            
            // Coming soon page
            'coming_soon' => [
                'en' => 'Coming Soon',
                'ar' => 'قريباً'
            ],
            'feature_under_development' => [
                'en' => 'This feature is currently under development and will be available soon.',
                'ar' => 'هذه الميزة قيد التطوير حالياً وستكون متاحة قريباً.'
            ],
            'go_back' => [
                'en' => 'Go Back',
                'ar' => 'العودة'
            ],
            'home' => [
                'en' => 'Home',
                'ar' => 'الرئيسية'
            ],
            
            // Messages
            'unauthorized_action' => [
                'en' => 'You are not authorized to perform this action',
                'ar' => 'غير مصرح لك بتنفيذ هذا الإجراء'
            ],
            'student_not_in_circle' => [
                'en' => 'The student is not in this circle',
                'ar' => 'الطالب ليس في هذه الحلقة'
            ],
            'report_updated_successfully' => [
                'en' => 'Report updated successfully',
                'ar' => 'تم تحديث التقرير بنجاح'
            ],
            'report_created_successfully' => [
                'en' => 'Report created successfully',
                'ar' => 'تم إنشاء التقرير بنجاح'
            ],
            'report_deleted_successfully' => [
                'en' => 'Report deleted successfully',
                'ar' => 'تم حذف التقرير بنجاح'
            ],
            'points_updated_successfully' => [
                'en' => 'Points updated successfully',
                'ar' => 'تم تحديث النقاط بنجاح'
            ],
            'student_not_in_your_circles' => [
                'en' => 'This student is not in any of your circles',
                'ar' => 'هذا الطالب ليس في أي من حلقاتك'
            ],
        ];
    }
} 