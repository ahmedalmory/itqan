<?php

namespace Database\Seeders\Translations;

class CircleTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     * 
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // Circle related translations
            'quran_circles' => [
                'ar' => 'حلقات القرآن',
                'en' => 'Quran Circles'
            ],
            'study_circles' => [
                'ar' => 'حلقات الدراسة',
                'en' => 'Study Circles'
            ],
            'select_circle' => [
                'ar' => 'اختر الحلقة',
                'en' => 'Select Circle'
            ],
            'select_circle_welcome_message' => [
                'ar' => 'اختر حلقة القرآن التي ترغب في الانضمام إليها',
                'en' => 'Choose the Quran circle you wish to join'
            ],
            'age_range' => [
                'ar' => 'نطاق العمر',
                'en' => 'Age Range'
            ],
            'circle_time' => [
                'ar' => 'وقت الحلقة',
                'en' => 'Circle Time'
            ],
            'join_circle' => [
                'ar' => 'انضم إلى الحلقة',
                'en' => 'Join Circle'
            ],
            'no_circles_available' => [
                'ar' => 'لا توجد حلقات متاحة',
                'en' => 'No circles available'
            ],
            'my_circles' => [
                'ar' => 'حلقاتي',
                'en' => 'My Circles'
            ],
            'no_circles_joined' => [
                'ar' => 'لم تنضم إلى أي حلقات بعد',
                'en' => 'No circles joined yet'
            ],
            'no_circles_found' => [
                'ar' => 'لم يتم العثور على حلقات',
                'en' => 'No circles found'
            ],
            'circle_details' => [
                'ar' => 'تفاصيل الحلقة',
                'en' => 'Circle Details'
            ],
            'back_to_circles' => [
                'ar' => 'العودة إلى الحلقات',
                'en' => 'Back to Circles'
            ],
            'back_to_circle' => [
                'ar' => 'العودة إلى الحلقة',
                'en' => 'Back to Circle'
            ],
            'description' => [
                'ar' => 'الوصف',
                'en' => 'Description'
            ],
            'view' => [
                'ar' => 'عرض',
                'en' => 'View'
            ],
            'whatsapp_group' => [
                'ar' => 'مجموعة واتساب',
                'en' => 'WhatsApp Group'
            ],
            'telegram_group' => [
                'ar' => 'مجموعة تيليجرام',
                'en' => 'Telegram Group'
            ],
            'max_students' => [
                'ar' => 'الحد الأقصى للطلاب',
                'en' => 'Maximum Students'
            ],
            'meeting_days' => [
                'ar' => 'أيام الاجتماع',
                'en' => 'Meeting Days'
            ],
            'meeting_time' => [
                'ar' => 'وقت الاجتماع',
                'en' => 'Meeting Time'
            ],
            'meeting_link' => [
                'ar' => 'رابط الاجتماع',
                'en' => 'Meeting Link'
            ],
            'not_specified' => [
                'ar' => 'غير محدد',
                'en' => 'Not Specified'
            ],
            'not_available' => [
                'ar' => 'غير متاح',
                'en' => 'Not Available'
            ],
            'join_meeting' => [
                'ar' => 'انضم إلى الاجتماع',
                'en' => 'Join Meeting'
            ],
            'communication_channels' => [
                'ar' => 'قنوات التواصل',
                'en' => 'Communication Channels'
            ],
            'no_communication_channels' => [
                'ar' => 'لا توجد قنوات تواصل متاحة',
                'en' => 'No communication channels available'
            ],
            'circle_statistics' => [
                'ar' => 'إحصاءات الحلقة',
                'en' => 'Circle Statistics'
            ],
            'enrolled_students' => [
                'ar' => 'الطلاب المسجلين',
                'en' => 'Enrolled Students'
            ],
            'available_slots' => [
                'ar' => 'المقاعد المتاحة',
                'en' => 'Available Slots'
            ],
            'enrollment_status' => [
                'ar' => 'حالة التسجيل',
                'en' => 'Enrollment Status'
            ],
            'manage_students' => [
                'ar' => 'إدارة الطلاب',
                'en' => 'Manage Students'
            ],
            'circle_supervisor' => [
                'ar' => 'مشرف الحلقة',
                'en' => 'Circle Supervisor'
            ],
            'no_supervisor_assigned' => [
                'ar' => 'لم يتم تعيين مشرف',
                'en' => 'No supervisor assigned'
            ],
            'students_list' => [
                'ar' => 'قائمة الطلاب',
                'en' => 'Students List'
            ],
            'no_students_in_this_circle' => [
                'ar' => 'لا يوجد طلاب في هذه الحلقة',
                'en' => 'No students in this circle'
            ],
            'joined_date' => [
                'ar' => 'تاريخ الانضمام',
                'en' => 'Joined Date'
            ],
            'edit_circle' => [
                'ar' => 'تعديل الحلقة',
                'en' => 'Edit Circle'
            ],
            'Edit Study Circle' => [
                'ar' => 'تعديل حلقة الدراسة',
                'en' => 'Edit Study Circle'
            ],
            'edit_circle_details' => [
                'ar' => 'تعديل تفاصيل الحلقة',
                'en' => 'Edit Circle Details'
            ],
            'circle_name' => [
                'ar' => 'اسم الحلقة',
                'en' => 'Circle Name'
            ],
            'Circle Name' => [
                'ar' => 'اسم الحلقة',
                'en' => 'Circle Name'
            ],
            'circle_name_managed_by_admin' => [
                'ar' => 'يمكن تغيير اسم الحلقة فقط بواسطة المسؤولين',
                'en' => 'Circle name can only be changed by administrators'
            ],
            'meeting_days_help' => [
                'ar' => 'مثال: الأحد، الثلاثاء، الخميس',
                'en' => 'E.g., Sunday, Tuesday, Thursday'
            ],
            'meeting_time_help' => [
                'ar' => 'مثال: 7:00 مساءً - 9:00 مساءً',
                'en' => 'E.g., 7:00 PM - 9:00 PM'
            ],
            'meeting_link_help' => [
                'ar' => 'رابط الاجتماع الافتراضي (زوم، جوجل ميت، إلخ)',
                'en' => 'Link to virtual meeting (Zoom, Google Meet, etc.)'
            ],
            'whatsapp_group_link' => [
                'ar' => 'رابط مجموعة واتساب',
                'en' => 'WhatsApp Group Link'
            ],
            'telegram_group_link' => [
                'ar' => 'رابط مجموعة تيليجرام',
                'en' => 'Telegram Group Link'
            ],
            'update_circle' => [
                'ar' => 'تحديث الحلقة',
                'en' => 'Update Circle'
            ],
            'browse_circles' => [
                'ar' => 'تصفح الحلقات',
                'en' => 'Browse Circles'
            ],
            'back_to_my_circles' => [
                'ar' => 'العودة إلى حلقاتي',
                'en' => 'Back to My Circles'
            ],
            'search_by_name_or_teacher' => [
                'ar' => 'البحث بالاسم أو المعلم',
                'en' => 'Search by name or teacher'
            ],
            'capacity' => [
                'ar' => 'السعة',
                'en' => 'Capacity'
            ],
            'unlimited' => [
                'ar' => 'غير محدود',
                'en' => 'Unlimited'
            ],
            'enroll' => [
                'ar' => 'التسجيل',
                'en' => 'Enroll'
            ],
            'no_available_circles' => [
                'ar' => 'لا توجد حلقات متاحة',
                'en' => 'No available circles'
            ],
            'circle_information' => [
                'ar' => 'معلومات الحلقة',
                'en' => 'Circle Information'
            ],
            'circles_count' => [
                'ar' => 'عدد الحلقات',
                'en' => 'Circles Count'
            ],
            'schedule' => [
                'ar' => 'الجدول',
                'en' => 'Schedule'
            ],
            'not_scheduled' => [
                'ar' => 'غير مجدول',
                'en' => 'Not Scheduled'
            ],
            'no_circles_enrolled' => [
                'ar' => 'لم تنضم إلى أي حلقات حتى الآن',
                'en' => 'You are not enrolled in any circles yet'
            ],
            'browse_available_circles' => [
                'ar' => 'تصفح الحلقات المتاحة',
                'en' => 'Browse Available Circles'
            ],
            'circles' => [
                'ar' => 'الحلقات',
                'en' => 'Circles'
            ],
        ];
    }
} 