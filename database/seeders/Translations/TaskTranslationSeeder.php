<?php

namespace Database\Seeders\Translations;

use Database\Seeders\Translations\AbstractTranslationSeeder;

class TaskTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     *
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // Task Management
            'tasks' => [
                'ar' => 'المهام',
                'en' => 'Tasks',
            ],
            'Tasks Management' => [
                'ar' => 'إدارة المهام',
                'en' => 'Tasks Management',
            ],
            'Create Task' => [
                'ar' => 'إنشاء مهمة',
                'en' => 'Create Task',
            ],
            'Create New Task' => [
                'ar' => 'إنشاء مهمة جديدة',
                'en' => 'Create New Task',
            ],
            'Edit Task' => [
                'ar' => 'تعديل المهمة',
                'en' => 'Edit Task',
            ],
            'Delete Task' => [
                'ar' => 'حذف المهمة',
                'en' => 'Delete Task',
            ],
            'View Task' => [
                'ar' => 'عرض المهمة',
                'en' => 'View Task',
            ],
            'Task Details' => [
                'ar' => 'تفاصيل المهمة',
                'en' => 'Task Details',
            ],
            
            // Task Properties
            'Task Title' => [
                'ar' => 'عنوان المهمة',
                'en' => 'Task Title',
            ],
            'Task Description' => [
                'ar' => 'وصف المهمة',
                'en' => 'Task Description',
            ],
            'Priority' => [
                'ar' => 'الأولوية',
                'en' => 'Priority',
            ],
            'Status' => [
                'ar' => 'الحالة',
                'en' => 'Status',
            ],
            'Due Date' => [
                'ar' => 'تاريخ الاستحقاق',
                'en' => 'Due Date',
            ],
            'Assigned To' => [
                'ar' => 'مُعين إلى',
                'en' => 'Assigned To',
            ],
            'Created By' => [
                'ar' => 'أنشأها',
                'en' => 'Created By',
            ],
            'Department' => [
                'ar' => 'القسم',
                'en' => 'Department',
            ],
            'No Due Date' => [
                'ar' => 'لا يوجد تاريخ استحقاق',
                'en' => 'No Due Date',
            ],
            'No Department' => [
                'ar' => 'لا يوجد قسم',
                'en' => 'No Department',
            ],
            
            // Priority Levels
            'Low' => [
                'ar' => 'منخفض',
                'en' => 'Low',
            ],
            'Medium' => [
                'ar' => 'متوسط',
                'en' => 'Medium',
            ],
            'High' => [
                'ar' => 'عالي',
                'en' => 'High',
            ],
            'Urgent' => [
                'ar' => 'عاجل',
                'en' => 'Urgent',
            ],
            
            // Status Types
            'Active' => [
                'ar' => 'نشط',
                'en' => 'Active',
            ],
            'Inactive' => [
                'ar' => 'غير نشط',
                'en' => 'Inactive',
            ],
            'Completed' => [
                'ar' => 'مكتمل',
                'en' => 'Completed',
            ],
            'Cancelled' => [
                'ar' => 'ملغى',
                'en' => 'Cancelled',
            ],
            'Pending' => [
                'ar' => 'في الانتظار',
                'en' => 'Pending',
            ],
            'In Progress' => [
                'ar' => 'قيد التنفيذ',
                'en' => 'In Progress',
            ],
            'Overdue' => [
                'ar' => 'متأخر',
                'en' => 'Overdue',
            ],
            
            // Task Assignment
            'Assignment' => [
                'ar' => 'التكليف',
                'en' => 'Assignment',
            ],
            'Assign To' => [
                'ar' => 'تعيين إلى',
                'en' => 'Assign To',
            ],
            'Assign to Role' => [
                'ar' => 'تعيين للدور',
                'en' => 'Assign to Role',
            ],
            'Assign to Specific User' => [
                'ar' => 'تعيين لمستخدم محدد',
                'en' => 'Assign to Specific User',
            ],
            'Select Role' => [
                'ar' => 'اختر الدور',
                'en' => 'Select Role',
            ],
            'Select User' => [
                'ar' => 'اختر المستخدم',
                'en' => 'Select User',
            ],
            'Department Admin' => [
                'ar' => 'مدير القسم',
                'en' => 'Department Admin',
            ],
            'Teacher' => [
                'ar' => 'معلم',
                'en' => 'Teacher',
            ],
            'Supervisor' => [
                'ar' => 'مشرف',
                'en' => 'Supervisor',
            ],
            
            // Recurring Tasks
            'Recurring Task' => [
                'ar' => 'مهمة متكررة',
                'en' => 'Recurring Task',
            ],
            'Recurring' => [
                'ar' => 'متكرر',
                'en' => 'Recurring',
            ],
            'Recurring Days' => [
                'ar' => 'أيام التكرار',
                'en' => 'Recurring Days',
            ],
            'Excluded Dates' => [
                'ar' => 'التواريخ المستبعدة',
                'en' => 'Excluded Dates',
            ],
            'Enter dates to exclude (one per line, format: YYYY-MM-DD)' => [
                'ar' => 'أدخل التواريخ المستبعدة (واحد في كل سطر، الصيغة: YYYY-MM-DD)',
                'en' => 'Enter dates to exclude (one per line, format: YYYY-MM-DD)',
            ],
            'Enter dates to exclude from recurring assignments, one per line (format: YYYY-MM-DD)' => [
                'ar' => 'أدخل التواريخ المستبعدة من التكليفات المتكررة، واحد في كل سطر (الصيغة: YYYY-MM-DD)',
                'en' => 'Enter dates to exclude from recurring assignments, one per line (format: YYYY-MM-DD)',
            ],
            
            // Days of Week
            'Monday' => [
                'ar' => 'الاثنين',
                'en' => 'Monday',
            ],
            'Tuesday' => [
                'ar' => 'الثلاثاء',
                'en' => 'Tuesday',
            ],
            'Wednesday' => [
                'ar' => 'الأربعاء',
                'en' => 'Wednesday',
            ],
            'Thursday' => [
                'ar' => 'الخميس',
                'en' => 'Thursday',
            ],
            'Friday' => [
                'ar' => 'الجمعة',
                'en' => 'Friday',
            ],
            'Saturday' => [
                'ar' => 'السبت',
                'en' => 'Saturday',
            ],
            'Sunday' => [
                'ar' => 'الأحد',
                'en' => 'Sunday',
            ],
            
            // Task Completion
            'Mark as Done' => [
                'ar' => 'وضع علامة كمكتمل',
                'en' => 'Mark as Done',
            ],
            'Mark Done' => [
                'ar' => 'وضع علامة',
                'en' => 'Mark Done',
            ],
            'Mark as Not Done' => [
                'ar' => 'وضع علامة كغير مكتمل',
                'en' => 'Mark as Not Done',
            ],
            'Mark Task as Done' => [
                'ar' => 'وضع علامة على المهمة كمكتملة',
                'en' => 'Mark Task as Done',
            ],
            'Mark Task as Not Done' => [
                'ar' => 'وضع علامة على المهمة كغير مكتملة',
                'en' => 'Mark Task as Not Done',
            ],
            'Done' => [
                'ar' => 'مكتمل',
                'en' => 'Done',
            ],
            'Not Done' => [
                'ar' => 'غير مكتمل',
                'en' => 'Not Done',
            ],
            'Task marked as completed' => [
                'ar' => 'تم وضع علامة على المهمة كمكتملة',
                'en' => 'Task marked as completed',
            ],
            'Task marked as not completed' => [
                'ar' => 'تم وضع علامة على المهمة كغير مكتملة',
                'en' => 'Task marked as not completed',
            ],
            
            // Bulk Actions
            'Mark Selected as Done' => [
                'ar' => 'وضع علامة على المحددة كمكتملة',
                'en' => 'Mark Selected as Done',
            ],
            'Select All' => [
                'ar' => 'اختيار الكل',
                'en' => 'Select All',
            ],
            'Bulk Action' => [
                'ar' => 'إجراء جماعي',
                'en' => 'Bulk Action',
            ],
            'Bulk Complete' => [
                'ar' => 'إكمال جماعي',
                'en' => 'Bulk Complete',
            ],
            'Marked :count tasks as completed' => [
                'ar' => 'تم وضع علامة على :count مهام كمكتملة',
                'en' => 'Marked :count tasks as completed',
            ],
            'Please select at least one task to complete.' => [
                'ar' => 'يرجى اختيار مهمة واحدة على الأقل للإكمال.',
                'en' => 'Please select at least one task to complete.',
            ],
            
            // Today's Tasks
            'Today\'s Tasks' => [
                'ar' => 'مهام اليوم',
                'en' => 'Today\'s Tasks',
            ],
            'Tasks for Today' => [
                'ar' => 'مهام اليوم',
                'en' => 'Tasks for Today',
            ],
            'Tasks for' => [
                'ar' => 'مهام',
                'en' => 'Tasks for',
            ],
            'No tasks for today!' => [
                'ar' => 'لا توجد مهام لليوم!',
                'en' => 'No tasks for today!',
            ],
            'You have no tasks assigned for today. Great job!' => [
                'ar' => 'لا توجد مهام مكلف بها اليوم. عمل رائع!',
                'en' => 'You have no tasks assigned for today. Great job!',
            ],
            'Total Tasks' => [
                'ar' => 'إجمالي المهام',
                'en' => 'Total Tasks',
            ],
            'Active Tasks' => [
                'ar' => 'المهام النشطة',
                'en' => 'Active Tasks',
            ],
            'Today\'s Assignments' => [
                'ar' => 'مهام اليوم',
                'en' => 'Today\'s Assignments',
            ],
            'Remaining' => [
                'ar' => 'المتبقي',
                'en' => 'Remaining',
            ],
            'Progress' => [
                'ar' => 'التقدم',
                'en' => 'Progress',
            ],
            'Completed: :count/:total' => [
                'ar' => 'مكتمل: :count/:total',
                'en' => 'Completed: :count/:total',
            ],
            'Remaining: :count' => [
                'ar' => 'المتبقي: :count',
                'en' => 'Remaining: :count',
            ],
            
            // Task Statistics
            'Completion Rate' => [
                'ar' => 'معدل الإكمال',
                'en' => 'Completion Rate',
            ],
            'My Tasks' => [
                'ar' => 'مهامي',
                'en' => 'My Tasks',
            ],
            'My Completed Tasks' => [
                'ar' => 'مهامي المكتملة',
                'en' => 'My Completed Tasks',
            ],
            'My Pending Tasks' => [
                'ar' => 'مهامي المعلقة',
                'en' => 'My Pending Tasks',
            ],
            
            // Task Notes and Comments
            'Notes' => [
                'ar' => 'الملاحظات',
                'en' => 'Notes',
            ],
            'Comments' => [
                'ar' => 'التعليقات',
                'en' => 'Comments',
            ],
            'Add notes for selected tasks (optional)' => [
                'ar' => 'أضف ملاحظات للمهام المحددة (اختياري)',
                'en' => 'Add notes for selected tasks (optional)',
            ],
            'Add any comments or notes about completing this task...' => [
                'ar' => 'أضف أي تعليقات أو ملاحظات حول إكمال هذه المهمة...',
                'en' => 'Add any comments or notes about completing this task...',
            ],
            'Add any comments or notes...' => [
                'ar' => 'أضف أي تعليقات أو ملاحظات...',
                'en' => 'Add any comments or notes...',
            ],
            'Completed on :date' => [
                'ar' => 'تم الإكمال في :date',
                'en' => 'Completed on :date',
            ],
            'By: :name' => [
                'ar' => 'بواسطة: :name',
                'en' => 'By: :name',
            ],
            
            // Sharing and Reports
            'Share Report' => [
                'ar' => 'مشاركة التقرير',
                'en' => 'Share Report',
            ],
            'Share Today\'s Report' => [
                'ar' => 'مشاركة تقرير اليوم',
                'en' => 'Share Today\'s Report',
            ],
            'Daily Tasks Report' => [
                'ar' => 'تقرير المهام اليومية',
                'en' => 'Daily Tasks Report',
            ],
            'Report Text' => [
                'ar' => 'نص التقرير',
                'en' => 'Report Text',
            ],
            'Copy to Clipboard' => [
                'ar' => 'نسخ إلى الحافظة',
                'en' => 'Copy to Clipboard',
            ],
            'Copy' => [
                'ar' => 'نسخ',
                'en' => 'Copy',
            ],
            'Copied!' => [
                'ar' => 'تم النسخ!',
                'en' => 'Copied!',
            ],
            'You can copy this text and share it on WhatsApp or any messaging app.' => [
                'ar' => 'يمكنك نسخ هذا النص ومشاركته على الواتساب أو أي تطبيق مراسلة.',
                'en' => 'You can copy this text and share it on WhatsApp or any messaging app.',
            ],
            'Copy this text and share it on WhatsApp or any messaging app.' => [
                'ar' => 'انسخ هذا النص وشاركه على الواتساب أو أي تطبيق مراسلة.',
                'en' => 'Copy this text and share it on WhatsApp or any messaging app.',
            ],
            'Summary' => [
                'ar' => 'الملخص',
                'en' => 'Summary',
            ],
            'Completed Tasks' => [
                'ar' => 'المهام المكتملة',
                'en' => 'Completed Tasks',
            ],
            'Pending Tasks' => [
                'ar' => 'المهام المعلقة',
                'en' => 'Pending Tasks',
            ],
            
            // Filters and Search
            'Filters' => [
                'ar' => 'المرشحات',
                'en' => 'Filters',
            ],
            'All Statuses' => [
                'ar' => 'جميع الحالات',
                'en' => 'All Statuses',
            ],
            'All Priorities' => [
                'ar' => 'جميع الأولويات',
                'en' => 'All Priorities',
            ],
            'All Departments' => [
                'ar' => 'جميع الأقسام',
                'en' => 'All Departments',
            ],
            'Search' => [
                'ar' => 'البحث',
                'en' => 'Search',
            ],
            'Search tasks...' => [
                'ar' => 'البحث في المهام...',
                'en' => 'Search tasks...',
            ],
            'Filter' => [
                'ar' => 'تصفية',
                'en' => 'Filter',
            ],
            'Clear' => [
                'ar' => 'مسح',
                'en' => 'Clear',
            ],
            
            // Daily Assignments
            'Generate Daily Assignments' => [
                'ar' => 'إنشاء مهام يومية',
                'en' => 'Generate Daily Assignments',
            ],
            'Generate' => [
                'ar' => 'إنشاء',
                'en' => 'Generate',
            ],
            'Date' => [
                'ar' => 'التاريخ',
                'en' => 'Date',
            ],
            'This will generate task assignments for all active recurring tasks for the selected date.' => [
                'ar' => 'سيؤدي هذا إلى إنشاء مهام لجميع المهام المتكررة النشطة للتاريخ المحدد.',
                'en' => 'This will generate task assignments for all active recurring tasks for the selected date.',
            ],
            'Generated :count task assignments for :date' => [
                'ar' => 'تم إنشاء :count مهمة لتاريخ :date',
                'en' => 'Generated :count task assignments for :date',
            ],
            
            // Messages and Notifications
            'Task created successfully' => [
                'ar' => 'تم إنشاء المهمة بنجاح',
                'en' => 'Task created successfully',
            ],
            'Task updated successfully' => [
                'ar' => 'تم تحديث المهمة بنجاح',
                'en' => 'Task updated successfully',
            ],
            'Task deleted successfully' => [
                'ar' => 'تم حذف المهمة بنجاح',
                'en' => 'Task deleted successfully',
            ],
            'You do not have permission to create tasks for this department' => [
                'ar' => 'ليس لديك صلاحية لإنشاء مهام لهذا القسم',
                'en' => 'You do not have permission to create tasks for this department',
            ],
            'You do not have permission to assign tasks to this department' => [
                'ar' => 'ليس لديك صلاحية لتعيين مهام لهذا القسم',
                'en' => 'You do not have permission to assign tasks to this department',
            ],
            'You are not authorized to complete this task' => [
                'ar' => 'ليس لديك صلاحية لإكمال هذه المهمة',
                'en' => 'You are not authorized to complete this task',
            ],
            'You are not authorized to modify this task' => [
                'ar' => 'ليس لديك صلاحية لتعديل هذه المهمة',
                'en' => 'You are not authorized to modify this task',
            ],
            'Are you sure you want to delete this task?' => [
                'ar' => 'هل أنت متأكد من أنك تريد حذف هذه المهمة؟',
                'en' => 'Are you sure you want to delete this task?',
            ],
            'Are you sure you want to mark this task as not completed?' => [
                'ar' => 'هل أنت متأكد من أنك تريد وضع علامة على هذه المهمة كغير مكتملة؟',
                'en' => 'Are you sure you want to mark this task as not completed?',
            ],
            'Mark this task as not completed?' => [
                'ar' => 'وضع علامة على هذه المهمة كغير مكتملة؟',
                'en' => 'Mark this task as not completed?',
            ],
            
            // Navigation
            'Back to Tasks' => [
                'ar' => 'العودة إلى المهام',
                'en' => 'Back to Tasks',
            ],
            'View All Tasks' => [
                'ar' => 'عرض جميع المهام',
                'en' => 'View All Tasks',
            ],
            'Task List' => [
                'ar' => 'قائمة المهام',
                'en' => 'Task List',
            ],
            
            // General Actions
            'Actions' => [
                'ar' => 'الإجراءات',
                'en' => 'Actions',
            ],
            'Edit' => [
                'ar' => 'تعديل',
                'en' => 'Edit',
            ],
            'Delete' => [
                'ar' => 'حذف',
                'en' => 'Delete',
            ],
            'View' => [
                'ar' => 'عرض',
                'en' => 'View',
            ],
            'Cancel' => [
                'ar' => 'إلغاء',
                'en' => 'Cancel',
            ],
            'Save' => [
                'ar' => 'حفظ',
                'en' => 'Save',
            ],
            'Close' => [
                'ar' => 'إغلاق',
                'en' => 'Close',
            ],
            'Yes' => [
                'ar' => 'نعم',
                'en' => 'Yes',
            ],
            'No' => [
                'ar' => 'لا',
                'en' => 'No',
            ],
            
            // Empty States
            'No tasks found' => [
                'ar' => 'لم يتم العثور على مهام',
                'en' => 'No tasks found',
            ],
            'Create your first task to get started' => [
                'ar' => 'أنشئ أول مهمة لك للبدء',
                'en' => 'Create your first task to get started',
            ],
            'No tasks assigned' => [
                'ar' => 'لا توجد مهام مكلف بها',
                'en' => 'No tasks assigned',
            ],
            
            // Task Completion - New entries for comment functionality
            'Complete Task' => [
                'ar' => 'إكمال المهمة',
                'en' => 'Complete Task',
            ],
            'Completion Notes' => [
                'ar' => 'ملاحظات الإكمال',
                'en' => 'Completion Notes',
            ],
            'Mark as Complete' => [
                'ar' => 'وضع علامة كمكتمل',
                'en' => 'Mark as Complete',
            ],
            'Mark as Incomplete' => [
                'ar' => 'وضع علامة كغير مكتمل',
                'en' => 'Mark as Incomplete',
            ],
            'Mark Complete' => [
                'ar' => 'وضع علامة مكتمل',
                'en' => 'Mark Complete',
            ],
            'Mark Incomplete' => [
                'ar' => 'وضع علامة غير مكتمل',
                'en' => 'Mark Incomplete',
            ],
            'Maximum 1000 characters' => [
                'ar' => 'أقصى حد 1000 حرف',
                'en' => 'Maximum 1000 characters',
            ],
            'Optional' => [
                'ar' => 'اختياري',
                'en' => 'Optional',
            ],
            'All Tasks' => [
                'ar' => 'جميع المهام',
                'en' => 'All Tasks',
            ],
            'This task can only be completed on its assigned date.' => [
                'ar' => 'لا يمكن إكمال هذه المهمة إلا في تاريخ تعيينها.',
                'en' => 'This task can only be completed on its assigned date.',
            ],
            'Mark this task as complete when you have finished it.' => [
                'ar' => 'ضع علامة على هذه المهمة كمكتملة عند الانتهاء منها.',
                'en' => 'Mark this task as complete when you have finished it.',
            ],
            'Completed at' => [
                'ar' => 'مكتمل في',
                'en' => 'Completed at',
            ],
            'Completed by' => [
                'ar' => 'مكتمل بواسطة',
                'en' => 'Completed by',
            ],
            'Assignment Date' => [
                'ar' => 'تاريخ التكليف',
                'en' => 'Assignment Date',
            ],
            'Assignment Type' => [
                'ar' => 'نوع التكليف',
                'en' => 'Assignment Type',
            ],
            'Recent Completions' => [
                'ar' => 'الإكمالات الأخيرة',
                'en' => 'Recent Completions',
            ],
            'User' => [
                'ar' => 'المستخدم',
                'en' => 'User',
            ],
            'Instructions' => [
                'ar' => 'التعليمات',
                'en' => 'Instructions',
            ],
            'Recurring Schedule' => [
                'ar' => 'الجدول المتكرر',
                'en' => 'Recurring Schedule',
            ],
            'No assignments for today' => [
                'ar' => 'لا توجد مهام لليوم',
                'en' => 'No assignments for today',
            ],
            'No recent completions' => [
                'ar' => 'لا توجد إكمالات حديثة',
                'en' => 'No recent completions',
            ],
            'Task Completed' => [
                'ar' => 'تم إكمال المهمة',
                'en' => 'Task Completed',
            ],
            'You have no tasks assigned to you at the moment' => [
                'ar' => 'لا توجد مهام مكلف بها في الوقت الحالي',
                'en' => 'You have no tasks assigned to you at the moment',
            ],
            'No tasks for today' => [
                'ar' => 'لا توجد مهام لليوم',
                'en' => 'No tasks for today',
            ],
            'You have no tasks assigned for today' => [
                'ar' => 'لا توجد مهام مكلف بها لليوم',
                'en' => 'You have no tasks assigned for today',
            ],
            
            // Form Validation
            'Select Priority' => [
                'ar' => 'اختر الأولوية',
                'en' => 'Select Priority',
            ],
            'Select Department' => [
                'ar' => 'اختر القسم',
                'en' => 'Select Department',
            ],
            'Select Status' => [
                'ar' => 'اختر الحالة',
                'en' => 'Select Status',
            ],
            'Description' => [
                'ar' => 'الوصف',
                'en' => 'Description',
            ],
            'Title' => [
                'ar' => 'العنوان',
                'en' => 'Title',
            ],
        ];
    }
} 