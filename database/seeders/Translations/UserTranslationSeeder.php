<?php

namespace Database\Seeders\Translations;

class UserTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     * 
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // User information and roles
            'age' => [
                'ar' => 'العمر',
                'en' => 'Age'
            ],
            'gender' => [
                'ar' => 'الجنس',
                'en' => 'Gender'
            ],
            'male' => [
                'ar' => 'ذكر',
                'en' => 'Male'
            ],
            'female' => [
                'ar' => 'أنثى',
                'en' => 'Female'
            ],
            'country' => [
                'ar' => 'الدولة',
                'en' => 'Country'
            ],
            'select_country' => [
                'ar' => 'اختر الدولة',
                'en' => 'Select Country'
            ],
            'select_gender' => [
                'ar' => 'اختر الجنس',
                'en' => 'Select Gender'
            ],
            'role' => [
                'ar' => 'الدور',
                'en' => 'Role'
            ],
            'super_admin' => [
                'ar' => 'مشرف عام',
                'en' => 'Super Admin'
            ],
            'department_admin' => [
                'ar' => 'مشرف قسم',
                'en' => 'Department Admin'
            ],
            'teacher' => [
                'ar' => 'معلم',
                'en' => 'Teacher'
            ],
            'supervisor' => [
                'ar' => 'مشرف',
                'en' => 'Supervisor'
            ],
            'student' => [
                'ar' => 'طالب',
                'en' => 'Student'
            ],
            'profile' => [
                'ar' => 'الملف الشخصي',
                'en' => 'Profile'
            ],
            'User Information' => [
                'ar' => 'معلومات المستخدم',
                'en' => 'User Information'
            ],
            'Not provided' => [
                'ar' => 'غير متوفر',
                'en' => 'Not provided'
            ],
            'Not specified' => [
                'ar' => 'غير محدد',
                'en' => 'Not specified'
            ],
            'national_id' => [
                'ar' => 'رقم الهوية',
                'en' => 'National ID'
            ],
            'Email Verified' => [
                'ar' => 'البريد الإلكتروني مؤكد',
                'en' => 'Email Verified'
            ],
            'Verified on' => [
                'ar' => 'تم التحقق في',
                'en' => 'Verified on'
            ],
            'Not verified' => [
                'ar' => 'غير مؤكد',
                'en' => 'Not verified'
            ],
            'Registered On' => [
                'ar' => 'تاريخ التسجيل',
                'en' => 'Registered On'
            ],
            'Last Updated' => [
                'ar' => 'آخر تحديث',
                'en' => 'Last Updated'
            ],
            'back_to_profile' => [
                'ar' => 'العودة إلى الملف الشخصي',
                'en' => 'Back to Profile'
            ],
            'Users Management' => [
                'ar' => 'إدارة المستخدمين',
                'en' => 'Users Management'
            ],
            'Add New User' => [
                'ar' => 'إضافة مستخدم جديد',
                'en' => 'Add New User'
            ],
            'Search users...' => [
                'ar' => 'البحث عن مستخدمين...',
                'en' => 'Search users...'
            ],
            'All Roles' => [
                'ar' => 'جميع الأدوار',
                'en' => 'All Roles'
            ],
            'All Status' => [
                'ar' => 'جميع الحالات',
                'en' => 'All Status'
            ],
            'You' => [
                'ar' => 'أنت',
                'en' => 'You'
            ],
            'No users found' => [
                'ar' => 'لم يتم العثور على مستخدمين',
                'en' => 'No users found'
            ],
            'Confirm Delete' => [
                'ar' => 'تأكيد الحذف',
                'en' => 'Confirm Delete'
            ],
            'Are you sure you want to delete user' => [
                'ar' => 'هل أنت متأكد من حذف المستخدم',
                'en' => 'Are you sure you want to delete user'
            ],
            'This action cannot be undone.' => [
                'ar' => 'لا يمكن التراجع عن هذا الإجراء.',
                'en' => 'This action cannot be undone.'
            ],
            'Back to Users' => [
                'ar' => 'العودة إلى المستخدمين',
                'en' => 'Back to Users'
            ],
            'Create User' => [
                'ar' => 'إنشاء مستخدم',
                'en' => 'Create User'
            ],
            'Update User' => [
                'ar' => 'تحديث المستخدم',
                'en' => 'Update User'
            ],
            'Edit User' => [
                'ar' => 'تعديل المستخدم',
                'en' => 'Edit User'
            ],
            'User Details' => [
                'ar' => 'تفاصيل المستخدم',
                'en' => 'User Details'
            ],
            'Delete User' => [
                'ar' => 'حذف المستخدم',
                'en' => 'Delete User'
            ],
        ];
    }
} 