<?php

namespace Database\Seeders\Translations;

use App\Models\Language;
use App\Models\AppTranslation;

class SupervisorTranslationSeeder extends AbstractTranslationSeeder
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
            'supervised_circles' => [
                'en' => 'Supervised Circles',
                'ar' => 'الحلقات المشرف عليها',
            ],
            'assigned_circles' => [
                'en' => 'Assigned Circles',
                'ar' => 'الحلقات المسندة',
            ],
            'teachers' => [
                'en' => 'Teachers',
                'ar' => 'المعلمون',
            ],
            'total_teachers' => [
                'en' => 'Total Teachers',
                'ar' => 'إجمالي المعلمين',
            ],
            'reports' => [
                'en' => 'Reports',
                'ar' => 'التقارير',
            ],
            'pending_verification' => [
                'en' => 'Pending Verification',
                'ar' => 'بانتظار التحقق',
            ],
            'teacher' => [
                'en' => 'Teacher',
                'ar' => 'المعلم',
            ],
            'not_assigned' => [
                'en' => 'Not Assigned',
                'ar' => 'غير مسند',
            ],
            'no_assigned_circles' => [
                'en' => 'You do not have any assigned circles yet.',
                'ar' => 'ليس لديك أي حلقات مسندة حتى الآن.',
            ],
            'view_all_circles' => [
                'en' => 'View All Circles',
                'ar' => 'عرض جميع الحلقات',
            ],
            'teacher_performance' => [
                'en' => 'Teacher Performance',
                'ar' => 'أداء المعلم',
            ],
            'students' => [
                'en' => 'Students',
                'ar' => 'الطلاب',
            ],
            'rating' => [
                'en' => 'Rating',
                'ar' => 'التقييم',
            ],
            'teacher_name' => [
                'en' => 'Teacher Name',
                'ar' => 'اسم المعلم',
            ],
            'circle_name' => [
                'en' => 'Circle Name',
                'ar' => 'اسم الحلقة',
            ],

            // Circle management
            'My Supervised Circles' => [
                'en' => 'My Supervised Circles',
                'ar' => 'حلقاتي المشرف عليها',
            ],
            'You are not supervising any circles at the moment.' => [
                'en' => 'You are not supervising any circles at the moment.',
                'ar' => 'لا تشرف على أي حلقات في الوقت الحالي.',
            ],
            'Name' => [
                'en' => 'Name',
                'ar' => 'الاسم',
            ],
            'Department' => [
                'en' => 'Department',
                'ar' => 'القسم',
            ],
            'Students' => [
                'en' => 'Students',
                'ar' => 'الطلاب',
            ],
            'Actions' => [
                'en' => 'Actions',
                'ar' => 'الإجراءات',
            ],
            'View' => [
                'en' => 'View',
                'ar' => 'عرض',
            ],
            'Edit' => [
                'en' => 'Edit',
                'ar' => 'تعديل',
            ],
            'Manage Students' => [
                'en' => 'Manage Students',
                'ar' => 'إدارة الطلاب',
            ],
            'Not Assigned' => [
                'en' => 'Not Assigned',
                'ar' => 'غير مسند',
            ],
            'Unlimited' => [
                'en' => 'Unlimited',
                'ar' => 'غير محدود',
            ],
            
            // Circle details
            'Back to Circles' => [
                'en' => 'Back to Circles',
                'ar' => 'العودة إلى الحلقات',
            ],
            'Edit Circle' => [
                'en' => 'Edit Circle',
                'ar' => 'تعديل الحلقة',
            ],
            'Circle Information' => [
                'en' => 'Circle Information',
                'ar' => 'معلومات الحلقة',
            ],
            'Maximum Students' => [
                'en' => 'Maximum Students',
                'ar' => 'الحد الأقصى للطلاب',
            ],
            'Current Students' => [
                'en' => 'Current Students',
                'ar' => 'الطلاب الحاليين',
            ],
            'Circle Time' => [
                'en' => 'Circle Time',
                'ar' => 'وقت الحلقة',
            ],
            'Not specified' => [
                'en' => 'Not specified',
                'ar' => 'غير محدد',
            ],
            'WhatsApp Group' => [
                'en' => 'WhatsApp Group',
                'ar' => 'مجموعة واتساب',
            ],
            'Telegram Group' => [
                'en' => 'Telegram Group',
                'ar' => 'مجموعة تيليجرام',
            ],
            'Circle Description' => [
                'en' => 'Circle Description',
                'ar' => 'وصف الحلقة',
            ],
            'No description provided.' => [
                'en' => 'No description provided.',
                'ar' => 'لم يتم تقديم وصف.',
            ],
            'Students in this Circle' => [
                'en' => 'Students in this Circle',
                'ar' => 'الطلاب في هذه الحلقة',
            ],
            'No students enrolled in this circle yet.' => [
                'en' => 'No students enrolled in this circle yet.',
                'ar' => 'لا يوجد طلاب مسجلين في هذه الحلقة حتى الآن.',
            ],
            'Email' => [
                'en' => 'Email',
                'ar' => 'البريد الإلكتروني',
            ],
            'Phone' => [
                'en' => 'Phone',
                'ar' => 'الهاتف',
            ],
            'Points' => [
                'en' => 'Points',
                'ar' => 'النقاط',
            ],
            
            // Circle edit
            'Back to Circle' => [
                'en' => 'Back to Circle',
                'ar' => 'العودة إلى الحلقة',
            ],
            'Circle Name' => [
                'en' => 'Circle Name',
                'ar' => 'اسم الحلقة',
            ],
            'Description' => [
                'en' => 'Description',
                'ar' => 'الوصف',
            ],
            'Select Teacher' => [
                'en' => 'Select Teacher',
                'ar' => 'اختر المعلم',
            ],
            'WhatsApp Group Link' => [
                'en' => 'WhatsApp Group Link',
                'ar' => 'رابط مجموعة واتساب',
            ],
            'Telegram Group Link' => [
                'en' => 'Telegram Group Link',
                'ar' => 'رابط مجموعة تيليجرام',
            ],
            'Example: Every Sunday and Wednesday, 8:00 PM - 9:30 PM' => [
                'en' => 'Example: Every Sunday and Wednesday, 8:00 PM - 9:30 PM',
                'ar' => 'مثال: كل أحد وأربعاء، 8:00 مساءً - 9:30 مساءً',
            ],
            'Update Circle' => [
                'en' => 'Update Circle',
                'ar' => 'تحديث الحلقة',
            ],
            
            // Student management
            'Manage Students' => [
                'en' => 'Manage Students',
                'ar' => 'إدارة الطلاب',
            ],
            'Current Students' => [
                'en' => 'Current Students',
                'ar' => 'الطلاب الحاليين',
            ],
            'Add Student to Circle' => [
                'en' => 'Add Student to Circle',
                'ar' => 'إضافة طالب إلى الحلقة',
            ],
            'This circle has reached its maximum capacity.' => [
                'en' => 'This circle has reached its maximum capacity.',
                'ar' => 'وصلت هذه الحلقة إلى الحد الأقصى للسعة.',
            ],
            'Select Student' => [
                'en' => 'Select Student',
                'ar' => 'اختر الطالب',
            ],
            'Choose a student' => [
                'en' => 'Choose a student',
                'ar' => 'اختر طالبًا',
            ],
            'Add Student' => [
                'en' => 'Add Student',
                'ar' => 'إضافة طالب',
            ],
            'No more students available to add.' => [
                'en' => 'No more students available to add.',
                'ar' => 'لا يوجد المزيد من الطلاب المتاحين للإضافة.',
            ],
            'Are you sure you want to remove this student from the circle?' => [
                'en' => 'Are you sure you want to remove this student from the circle?',
                'ar' => 'هل أنت متأكد أنك تريد إزالة هذا الطالب من الحلقة؟',
            ],
            
            // Student details
            'Student Details' => [
                'en' => 'Student Details',
                'ar' => 'تفاصيل الطالب',
            ],
            'Student Information' => [
                'en' => 'Student Information',
                'ar' => 'معلومات الطالب',
            ],
            'Age' => [
                'en' => 'Age',
                'ar' => 'العمر',
            ],
            'Gender' => [
                'en' => 'Gender',
                'ar' => 'الجنس',
            ],
            'Country' => [
                'en' => 'Country',
                'ar' => 'البلد',
            ],
            'Performance Points' => [
                'en' => 'Performance Points',
                'ar' => 'نقاط الأداء',
            ],
            'No points recorded for this student yet.' => [
                'en' => 'No points recorded for this student yet.',
                'ar' => 'لم يتم تسجيل نقاط لهذا الطالب حتى الآن.',
            ],
            'Total Points' => [
                'en' => 'Total Points',
                'ar' => 'إجمالي النقاط',
            ],
            'Attendance' => [
                'en' => 'Attendance',
                'ar' => 'الحضور',
            ],
            'Memorization' => [
                'en' => 'Memorization',
                'ar' => 'الحفظ',
            ],
            'Recitation' => [
                'en' => 'Recitation',
                'ar' => 'التلاوة',
            ],
            'Tajweed' => [
                'en' => 'Tajweed',
                'ar' => 'التجويد',
            ],
            'Behavior' => [
                'en' => 'Behavior',
                'ar' => 'السلوك',
            ],
            'Daily Reports' => [
                'en' => 'Daily Reports',
                'ar' => 'التقارير اليومية',
            ],
            'No daily reports available for this student.' => [
                'en' => 'No daily reports available for this student.',
                'ar' => 'لا توجد تقارير يومية متاحة لهذا الطالب.',
            ],
            'Date' => [
                'en' => 'Date',
                'ar' => 'التاريخ',
            ],
            'Surah' => [
                'en' => 'Surah',
                'ar' => 'السورة',
            ],
            'From Verse' => [
                'en' => 'From Verse',
                'ar' => 'من الآية',
            ],
            'To Verse' => [
                'en' => 'To Verse',
                'ar' => 'إلى الآية',
            ],
            'Notes' => [
                'en' => 'Notes',
                'ar' => 'ملاحظات',
            ],
            'Present' => [
                'en' => 'Present',
                'ar' => 'حاضر',
            ],
            'Absent' => [
                'en' => 'Absent',
                'ar' => 'غائب',
            ],
        ];
    }
} 