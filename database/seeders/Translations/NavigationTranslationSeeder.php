<?php

namespace Database\Seeders\Translations;

class NavigationTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     * 
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // Navigation
            'dashboard' => [
                'ar' => 'لوحة التحكم',
                'en' => 'Dashboard'
            ],
            'settings' => [
                'ar' => 'الإعدادات',
                'en' => 'Settings'
            ],
            'admin_panel' => [
                'ar' => 'لوحة الإدارة',
                'en' => 'Admin Panel'
            ],
            'home' => [
                'ar' => 'الرئيسية',
                'en' => 'Home'
            ],
            'translations' => [
                'ar' => 'الترجمات',
                'en' => 'Translations'
            ],
            'back' => [
                'ar' => 'رجوع',
                'en' => 'Back'
            ],
            'back_to_dashboard' => [
                'ar' => 'العودة إلى لوحة التحكم',
                'en' => 'Back to Dashboard'
            ],
            'language_management' => [
                'ar' => 'إدارة اللغات',
                'en' => 'Language Management'
            ],
            'languages' => [
                'ar' => 'اللغات',
                'en' => 'Languages'
            ],
            'Language Name' => [
                'ar' => 'اسم اللغة',
                'en' => 'Language Name'
            ],
            'Language Code' => [
                'ar' => 'رمز اللغة',
                'en' => 'Language Code'
            ],
            'Text Direction' => [
                'ar' => 'اتجاه النص',
                'en' => 'Text Direction'
            ],
            'Left to Right (LTR)' => [
                'ar' => 'من اليسار إلى اليمين',
                'en' => 'Left to Right (LTR)'
            ],
            'Right to Left (RTL)' => [
                'ar' => 'من اليمين إلى اليسار',
                'en' => 'Right to Left (RTL)'
            ],
            'Default languages must remain active' => [
                'ar' => 'يجب أن تبقى اللغات الافتراضية نشطة',
                'en' => 'Default languages must remain active'
            ],
            'Edit Language' => [
                'ar' => 'تعديل اللغة',
                'en' => 'Edit Language'
            ],
            'Default language codes cannot be changed' => [
                'ar' => 'لا يمكن تغيير رموز اللغة الافتراضية',
                'en' => 'Default language codes cannot be changed'
            ],
            'Update Language' => [
                'ar' => 'تحديث اللغة',
                'en' => 'Update Language'
            ],
            'arabic_language' => [
                'ar' => 'العربية',
                'en' => 'Arabic'
            ],
            'english_language' => [
                'ar' => 'الإنجليزية',
                'en' => 'English'
            ],
        ];
    }
} 