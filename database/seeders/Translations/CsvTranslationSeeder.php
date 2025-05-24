<?php

namespace Database\Seeders\Translations;

class CsvTranslationSeeder extends AbstractTranslationSeeder
{
    /**
     * Get translations to be seeded
     * 
     * @return array
     */
    protected function getTranslations(): array
    {
        return [
            // CSV Import/Export
            'Import CSV' => [
                'ar' => 'استيراد ملف CSV',
                'en' => 'Import CSV'
            ],
            'Export to CSV' => [
                'ar' => 'تصدير إلى CSV',
                'en' => 'Export to CSV'
            ],
            'CSV File' => [
                'ar' => 'ملف CSV',
                'en' => 'CSV File'
            ],
            'Import' => [
                'ar' => 'استيراد',
                'en' => 'Import'
            ],
            'Required columns' => [
                'ar' => 'الأعمدة المطلوبة',
                'en' => 'Required columns'
            ],
            'Optional columns' => [
                'ar' => 'الأعمدة الاختيارية',
                'en' => 'Optional columns'
            ],
            'Import Users from CSV' => [
                'ar' => 'استيراد المستخدمين من ملف CSV',
                'en' => 'Import Users from CSV'
            ],
            'Valid roles' => [
                'ar' => 'الأدوار الصالحة',
                'en' => 'Valid roles'
            ],
            'Import successful' => [
                'ar' => 'تم الاستيراد بنجاح',
                'en' => 'Import successful'
            ],
            'Import failed' => [
                'ar' => 'فشل الاستيراد',
                'en' => 'Import failed'
            ],
            'Invalid file format' => [
                'ar' => 'تنسيق الملف غير صالح',
                'en' => 'Invalid file format'
            ],
            'Missing required columns' => [
                'ar' => 'أعمدة مطلوبة مفقودة',
                'en' => 'Missing required columns'
            ],
            'Invalid role' => [
                'ar' => 'دور غير صالح',
                'en' => 'Invalid role'
            ],
            'Download Sample CSV' => [
                'ar' => 'تحميل نموذج CSV',
                'en' => 'Download Sample CSV'
            ],
            'Sample CSV file' => [
                'ar' => 'ملف CSV نموذجي',
                'en' => 'Sample CSV file'
            ],
            'Please select a CSV file to import' => [
                'ar' => 'الرجاء اختيار ملف CSV للاستيراد',
                'en' => 'Please select a CSV file to import'
            ],
            'The CSV file is empty' => [
                'ar' => 'ملف CSV فارغ',
                'en' => 'The CSV file is empty'
            ],
            'The CSV file must contain at least one user record' => [
                'ar' => 'يجب أن يحتوي ملف CSV على سجل مستخدم واحد على الأقل',
                'en' => 'The CSV file must contain at least one user record'
            ],
            'Invalid number of columns in row' => [
                'ar' => 'عدد الأعمدة غير صحيح في السطر',
                'en' => 'Invalid number of columns in row'
            ],
            'Invalid email format' => [
                'ar' => 'تنسيق البريد الإلكتروني غير صالح',
                'en' => 'Invalid email format'
            ],
            'Email already exists' => [
                'ar' => 'البريد الإلكتروني موجود مسبقاً',
                'en' => 'Email already exists'
            ],
            'Row' => [
                'ar' => 'السطر',
                'en' => 'Row'
            ],
            'Successfully imported' => [
                'ar' => 'تم الاستيراد بنجاح',
                'en' => 'Successfully imported'
            ],
            'users' => [
                'ar' => 'مستخدمين',
                'en' => 'users'
            ],
            'Default password for all imported users is:' => [
                'ar' => 'كلمة المرور الافتراضية لجميع المستخدمين المستوردين هي:',
                'en' => 'Default password for all imported users is:'
            ],
            'Import failed with errors' => [
                'ar' => 'فشل الاستيراد مع وجود أخطاء',
                'en' => 'Import failed with errors'
            ],
            'Imported' => [
                'ar' => 'تم استيراد',
                'en' => 'Imported'
            ],
            'users with some errors' => [
                'ar' => 'مستخدمين مع بعض الأخطاء',
                'en' => 'users with some errors'
            ],
            'Default values used for missing fields' => [
                'ar' => 'القيم الافتراضية المستخدمة للحقول المفقودة',
                'en' => 'Default values used for missing fields'
            ],
            'Name and Email are required' => [
                'ar' => 'الاسم والبريد الإلكتروني مطلوبان',
                'en' => 'Name and Email are required'
            ],
            'Name' => [
                'ar' => 'الاسم',
                'en' => 'Name'
            ],
            'Email' => [
                'ar' => 'البريد الإلكتروني',
                'en' => 'Email'
            ],
            'Phone' => [
                'ar' => 'رقم الهاتف',
                'en' => 'Phone'
            ],
            'Role' => [
                'ar' => 'الدور',
                'en' => 'Role'
            ],
            'Country' => [
                'ar' => 'الدولة',
                'en' => 'Country'
            ],
            'Password' => [
                'ar' => 'كلمة المرور',
                'en' => 'Password'
            ],
            'Status' => [
                'ar' => 'الحالة',
                'en' => 'Status'
            ],
            'Active' => [
                'ar' => 'نشط',
                'en' => 'Active'
            ],
            'student' => [
                'ar' => 'طالب',
                'en' => 'student'
            ],
            'teacher' => [
                'ar' => 'معلم',
                'en' => 'teacher'
            ],
            'supervisor' => [
                'ar' => 'مشرف',
                'en' => 'supervisor'
            ],
            'department_admin' => [
                'ar' => 'مشرف قسم',
                'en' => 'department admin'
            ],
            'Saudi Arabia' => [
                'ar' => 'المملكة العربية السعودية',
                'en' => 'Saudi Arabia'
            ]
        ];
    }
} 