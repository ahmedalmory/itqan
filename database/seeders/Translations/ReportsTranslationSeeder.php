<?php

namespace Database\Seeders\Translations;

class ReportsTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     *
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // Main Reports Section
            'reports' => [
                'ar' => 'التقارير',
                'en' => 'Reports',
            ],
            'reports_overview' => [
                'ar' => 'نظرة عامة على التقارير',
                'en' => 'Reports Overview',
            ],
            'detailed_reports' => [
                'ar' => 'التقارير التفصيلية',
                'en' => 'Detailed Reports',
            ],
            'comprehensive_statistics_and_insights' => [
                'ar' => 'إحصائيات ورؤى شاملة',
                'en' => 'Comprehensive Statistics and Insights',
            ],
            'advanced_analytics_and_insights' => [
                'ar' => 'تحليلات ورؤى متقدمة',
                'en' => 'Advanced Analytics and Insights',
            ],
            'back_to_overview' => [
                'ar' => 'العودة إلى النظرة العامة',
                'en' => 'Back to Overview',
            ],

            // Export Options
            'export' => [
                'ar' => 'تصدير',
                'en' => 'Export',
            ],
            'export_csv' => [
                'ar' => 'تصدير CSV',
                'en' => 'Export CSV',
            ],
            'export_excel' => [
                'ar' => 'تصدير Excel',
                'en' => 'Export Excel',
            ],

            // User Statistics
            'total_users' => [
                'ar' => 'إجمالي المستخدمين',
                'en' => 'Total Users',
            ],
            'total_students' => [
                'ar' => 'إجمالي الطلاب',
                'en' => 'Total Students',
            ],
            'total_teachers' => [
                'ar' => 'إجمالي المعلمين',
                'en' => 'Total Teachers',
            ],
            'total_supervisors' => [
                'ar' => 'إجمالي المشرفين',
                'en' => 'Total Supervisors',
            ],
            'total_circles' => [
                'ar' => 'إجمالي الحلقات',
                'en' => 'Total Circles',
            ],
            'active_students' => [
                'ar' => 'الطلاب النشطين',
                'en' => 'Active Students',
            ],
            'active_circles' => [
                'ar' => 'الحلقات النشطة',
                'en' => 'Active Circles',
            ],
            'supervisors' => [
                'ar' => 'المشرفون',
                'en' => 'Supervisors',
            ],
            'this_month' => [
                'ar' => 'هذا الشهر',
                'en' => 'This Month',
            ],
            'active' => [
                'ar' => 'نشط',
                'en' => 'Active',
            ],
            'inactive' => [
                'ar' => 'غير نشط',
                'en' => 'Inactive',
            ],

            // Gender Distribution
            'student_gender_distribution' => [
                'ar' => 'توزيع الطلاب حسب الجنس',
                'en' => 'Student Gender Distribution',
            ],
            'male_students' => [
                'ar' => 'الطلاب الذكور',
                'en' => 'Male Students',
            ],
            'female_students' => [
                'ar' => 'الطالبات الإناث',
                'en' => 'Female Students',
            ],
            'male' => [
                'ar' => 'ذكور',
                'en' => 'Male',
            ],
            'female' => [
                'ar' => 'إناث',
                'en' => 'Female',
            ],

            // Memorization Statistics
            'memorization_statistics' => [
                'ar' => 'إحصائيات الحفظ',
                'en' => 'Memorization Statistics',
            ],
            'total_memorized_pages' => [
                'ar' => 'إجمالي الصفحات المحفوظة',
                'en' => 'Total Memorized Pages',
            ],
            'total_revised_pages' => [
                'ar' => 'إجمالي الصفحات المراجعة',
                'en' => 'Total Revised Pages',
            ],
            'average_grade' => [
                'ar' => 'متوسط الدرجات',
                'en' => 'Average Grade',
            ],
            'total_reports' => [
                'ar' => 'إجمالي التقارير',
                'en' => 'Total Reports',
            ],
            'memorized' => [
                'ar' => 'محفوظ',
                'en' => 'Memorized',
            ],
            'revised' => [
                'ar' => 'مراجع',
                'en' => 'Revised',
            ],

            // Charts and Trends
            'user_registration_trend' => [
                'ar' => 'اتجاه تسجيل المستخدمين',
                'en' => 'User Registration Trend',
            ],
            'role_distribution' => [
                'ar' => 'توزيع الأدوار',
                'en' => 'Role Distribution',
            ],
            'memorization_progress_trend' => [
                'ar' => 'اتجاه تقدم الحفظ',
                'en' => 'Memorization Progress Trend',
            ],
            'new_registrations' => [
                'ar' => 'التسجيلات الجديدة',
                'en' => 'New Registrations',
            ],

            // Points Statistics
            'total_points_awarded' => [
                'ar' => 'إجمالي النقاط الممنوحة',
                'en' => 'Total Points Awarded',
            ],
            'total_points_spent' => [
                'ar' => 'إجمالي النقاط المستهلكة',
                'en' => 'Total Points Spent',
            ],
            'total_departments' => [
                'ar' => 'إجمالي الأقسام',
                'en' => 'Total Departments',
            ],
            'avg_students_per_circle' => [
                'ar' => 'متوسط الطلاب لكل حلقة',
                'en' => 'Avg Students Per Circle',
            ],

            // Task Management Statistics
            'task_management_statistics' => [
                'ar' => 'إحصائيات إدارة المهام',
                'en' => 'Task Management Statistics',
            ],
            'total_tasks' => [
                'ar' => 'إجمالي المهام',
                'en' => 'Total Tasks',
            ],
            'completed_tasks' => [
                'ar' => 'المهام المكتملة',
                'en' => 'Completed Tasks',
            ],
            'active_tasks' => [
                'ar' => 'المهام النشطة',
                'en' => 'Active Tasks',
            ],
            'completion_rate' => [
                'ar' => 'معدل الإنجاز',
                'en' => 'Completion Rate',
            ],

            // Advanced Charts
            'points_distribution' => [
                'ar' => 'توزيع النقاط',
                'en' => 'Points Distribution',
            ],
            'weekly_activity_pattern' => [
                'ar' => 'نمط النشاط الأسبوعي',
                'en' => 'Weekly Activity Pattern',
            ],
            'top_performing_circles' => [
                'ar' => 'أفضل الحلقات أداءً',
                'en' => 'Top Performing Circles',
            ],
            'department_performance' => [
                'ar' => 'أداء الأقسام',
                'en' => 'Department Performance',
            ],
            'students' => [
                'ar' => 'الطلاب',
                'en' => 'Students',
            ],

            // Tables
            'top_performing_students' => [
                'ar' => 'أفضل الطلاب أداءً',
                'en' => 'Top Performing Students',
            ],
            'rank' => [
                'ar' => 'الترتيب',
                'en' => 'Rank',
            ],
            'student_name' => [
                'ar' => 'اسم الطالب',
                'en' => 'Student Name',
            ],
            'total_points' => [
                'ar' => 'إجمالي النقاط',
                'en' => 'Total Points',
            ],
            'monthly_progress_summary' => [
                'ar' => 'ملخص التقدم الشهري',
                'en' => 'Monthly Progress Summary',
            ],
            'month' => [
                'ar' => 'الشهر',
                'en' => 'Month',
            ],

            // Age and Country Distribution
            'student_age_distribution' => [
                'ar' => 'توزيع الطلاب حسب العمر',
                'en' => 'Student Age Distribution',
            ],
            'student_country_distribution' => [
                'ar' => 'توزيع الطلاب حسب البلد',
                'en' => 'Student Country Distribution',
            ],

            // Circle Performance Details
            'circle_performance_details' => [
                'ar' => 'تفاصيل أداء الحلقات',
                'en' => 'Circle Performance Details',
            ],
            'circle_name' => [
                'ar' => 'اسم الحلقة',
                'en' => 'Circle Name',
            ],
            'teacher' => [
                'ar' => 'المعلم',
                'en' => 'Teacher',
            ],
            'supervisor' => [
                'ar' => 'المشرف',
                'en' => 'Supervisor',
            ],
            'students_count' => [
                'ar' => 'عدد الطلاب',
                'en' => 'Students Count',
            ],
            'status' => [
                'ar' => 'الحالة',
                'en' => 'Status',
            ],

            // Common Terms
            'registered_users' => [
                'ar' => 'المستخدمون المسجلون',
                'en' => 'Registered Users',
            ],
            'enrolled_students' => [
                'ar' => 'الطلاب المسجلون',
                'en' => 'Enrolled Students',
            ],
            'study_circles' => [
                'ar' => 'الحلقات الدراسية',
                'en' => 'Study Circles',
            ],
            'departments' => [
                'ar' => 'الأقسام',
                'en' => 'Departments',
            ],
            'view_details' => [
                'ar' => 'عرض التفاصيل',
                'en' => 'View Details',
            ],
            'no_data_available' => [
                'ar' => 'لا توجد بيانات متاحة',
                'en' => 'No Data Available',
            ],
            'loading' => [
                'ar' => 'جاري التحميل...',
                'en' => 'Loading...',
            ],
            'error_loading_data' => [
                'ar' => 'خطأ في تحميل البيانات',
                'en' => 'Error Loading Data',
            ],

            // Time Periods
            'last_month' => [
                'ar' => 'الشهر الماضي',
                'en' => 'Last Month',
            ],
            'last_3_months' => [
                'ar' => 'آخر 3 أشهر',
                'en' => 'Last 3 Months',
            ],
            'last_6_months' => [
                'ar' => 'آخر 6 أشهر',
                'en' => 'Last 6 Months',
            ],
            'last_year' => [
                'ar' => 'السنة الماضية',
                'en' => 'Last Year',
            ],
            'all_time' => [
                'ar' => 'كل الأوقات',
                'en' => 'All Time',
            ],

            // Performance Indicators
            'high_performance' => [
                'ar' => 'أداء عالي',
                'en' => 'High Performance',
            ],
            'medium_performance' => [
                'ar' => 'أداء متوسط',
                'en' => 'Medium Performance',
            ],
            'low_performance' => [
                'ar' => 'أداء منخفض',
                'en' => 'Low Performance',
            ],
            'improvement_needed' => [
                'ar' => 'يحتاج تحسين',
                'en' => 'Improvement Needed',
            ],
            'excellent' => [
                'ar' => 'ممتاز',
                'en' => 'Excellent',
            ],
            'good' => [
                'ar' => 'جيد',
                'en' => 'Good',
            ],
            'average' => [
                'ar' => 'متوسط',
                'en' => 'Average',
            ],
            'below_average' => [
                'ar' => 'دون المتوسط',
                'en' => 'Below Average',
            ],

            // Additional export-specific translations
            'age' => [
                'ar' => 'العمر',
                'en' => 'Age',
            ],
            'country' => [
                'ar' => 'البلد',
                'en' => 'Country',
            ],
            'memorized_pages' => [
                'ar' => 'الصفحات المحفوظة',
                'en' => 'Memorized Pages',
            ],
            'circles_with_students' => [
                'ar' => 'الحلقات التي بها طلاب',
                'en' => 'Circles with Students',
            ],

            // Statistical Summary Table
            'statistical_summary' => [
                'ar' => 'الملخص الإحصائي',
                'en' => 'Statistical Summary',
            ],
            'current_month' => [
                'ar' => 'الشهر الحالي',
                'en' => 'Current Month',
            ],
            'metric' => [
                'ar' => 'المؤشر',
                'en' => 'Metric',
            ],
            'total' => [
                'ar' => 'الإجمالي',
                'en' => 'Total',
            ],
            'growth' => [
                'ar' => 'النمو',
                'en' => 'Growth',
            ],
            'points_system' => [
                'ar' => 'نظام النقاط',
                'en' => 'Points System',
            ],
            'system_totals' => [
                'ar' => 'إجماليات النظام',
                'en' => 'System Totals',
            ],
            'last_updated' => [
                'ar' => 'آخر تحديث',
                'en' => 'Last Updated',
            ],
            'data_source' => [
                'ar' => 'مصدر البيانات',
                'en' => 'Data Source',
            ],
            'itqan_database' => [
                'ar' => 'قاعدة بيانات إتقان',
                'en' => 'Itqan Database',
            ],
        ];
    }
} 