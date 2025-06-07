<?php

namespace Database\Seeders\Translations;

class ProfileTranslationSeeder extends AbstractTranslationSeeder
{
    protected function getTranslations(): array
    {
        return [
            // Page Titles
            'change_password' => [
                'en' => 'Change Password',
                'ar' => 'تغيير كلمة المرور',
            ],
            'back_to_profile' => [
                'en' => 'Back to Profile',
                'ar' => 'العودة للملف الشخصي',
            ],

            // Form Labels
            'current_password' => [
                'en' => 'Current Password',
                'ar' => 'كلمة المرور الحالية',
            ],
            'new_password' => [
                'en' => 'New Password',
                'ar' => 'كلمة المرور الجديدة',
            ],
            'confirm_new_password' => [
                'en' => 'Confirm New Password',
                'ar' => 'تأكيد كلمة المرور الجديدة',
            ],
            'update_password' => [
                'en' => 'Update Password',
                'ar' => 'تحديث كلمة المرور',
            ],

            // Success Messages
            'password_updated_successfully' => [
                'en' => 'Password updated successfully',
                'ar' => 'تم تحديث كلمة المرور بنجاح',
            ],

            // Error Messages
            'current_password_incorrect' => [
                'en' => 'The current password is incorrect',
                'ar' => 'كلمة المرور الحالية غير صحيحة',
            ],
            'error_updating_password' => [
                'en' => 'Error updating password',
                'ar' => 'خطأ في تحديث كلمة المرور',
            ],
            'password_min_length' => [
                'en' => 'The password must be at least 8 characters',
                'ar' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل',
            ],
            'password_confirmation_match' => [
                'en' => 'The password confirmation does not match',
                'ar' => 'تأكيد كلمة المرور غير متطابق',
            ],
            'password_required' => [
                'en' => 'The password field is required',
                'ar' => 'حقل كلمة المرور مطلوب',
            ],
            'current_password_required' => [
                'en' => 'The current password field is required',
                'ar' => 'حقل كلمة المرور الحالية مطلوب',
            ],
            'password_confirmation_required' => [
                'en' => 'The password confirmation field is required',
                'ar' => 'حقل تأكيد كلمة المرور مطلوب',
            ],
        ];
    }
} 