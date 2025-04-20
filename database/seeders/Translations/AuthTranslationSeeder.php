<?php

namespace Database\Seeders\Translations;

class AuthTranslationSeeder extends AbstractTranslationSeeder
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
            'login' => [
                'en' => 'Login',
                'ar' => 'تسجيل الدخول'
            ],
            'register' => [
                'en' => 'Register',
                'ar' => 'التسجيل'
            ],
            'logout' => [
                'en' => 'Logout',
                'ar' => 'تسجيل الخروج'
            ],
            'email' => [
                'en' => 'Email',
                'ar' => 'البريد الإلكتروني'
            ],
            'password' => [
                'en' => 'Password',
                'ar' => 'كلمة المرور'
            ],
            'name' => [
                'en' => 'Name',
                'ar' => 'الاسم'
            ],
            'phone' => [
                'en' => 'Phone',
                'ar' => 'رقم الهاتف'
            ],
            'age' => [
                'en' => 'Age',
                'ar' => 'العمر'
            ],
            'gender' => [
                'en' => 'Gender',
                'ar' => 'الجنس'
            ],
            'male' => [
                'en' => 'Male',
                'ar' => 'ذكر'
            ],
            'female' => [
                'en' => 'Female',
                'ar' => 'أنثى'
            ],
            'country' => [
                'en' => 'Country',
                'ar' => 'الدولة'
            ],
            'select_country' => [
                'en' => 'Select Country',
                'ar' => 'اختر الدولة'
            ],
            'register_button' => [
                'en' => 'Register',
                'ar' => 'تسجيل'
            ],
            'login_button' => [
                'en' => 'Login',
                'ar' => 'دخول'
            ],
            'remember_me' => [
                'en' => 'Remember Me',
                'ar' => 'تذكرني'
            ],
            'already_have_account' => [
                'en' => 'Already have an account?',
                'ar' => 'لديك حساب بالفعل؟'
            ],
            'login_here' => [
                'en' => 'Login here',
                'ar' => 'تسجيل الدخول هنا'
            ],
            'no_account' => [
                'en' => 'Don\'t have an account?',
                'ar' => 'ليس لديك حساب؟'
            ],
            'register_here' => [
                'en' => 'Register here',
                'ar' => 'التسجيل هنا'
            ],
            'preferred_time' => [
                'en' => 'Preferred Time',
                'ar' => 'الوقت المفضل'
            ],
            'after_fajr' => [
                'en' => 'After Fajr',
                'ar' => 'بعد الفجر'
            ],
            'after_dhuhr' => [
                'en' => 'After Dhuhr',
                'ar' => 'بعد الظهر'
            ],
            'after_asr' => [
                'en' => 'After Asr',
                'ar' => 'بعد العصر'
            ],
            'after_maghrib' => [
                'en' => 'After Maghrib',
                'ar' => 'بعد المغرب'
            ],
            'after_isha' => [
                'en' => 'After Isha',
                'ar' => 'بعد العشاء'
            ],
            'select_gender' => [
                'en' => 'Select Gender',
                'ar' => 'اختر الجنس'
            ],
            'select_preferred_time' => [
                'en' => 'Select Preferred Time',
                'ar' => 'اختر الوقت المفضل'
            ],
            'confirm_password' => [
                'en' => 'Confirm Password',
                'ar' => 'تأكيد كلمة المرور'
            ],
            'register_welcome_message' => [
                'en' => 'Create a new account to join Quran circles',
                'ar' => 'أنشئ حسابًا جديدًا للانضمام إلى حلقات القرآن'
            ],
            'login_welcome_message' => [
                'en' => 'Login to access your Quran circles',
                'ar' => 'سجل دخولك لمتابعة حلقات القرآن'
            ],
            'role' => [
                'en' => 'Role',
                'ar' => 'الدور'
            ],
            'super_admin' => [
                'en' => 'Super Admin',
                'ar' => 'مشرف عام'
            ],
            'department_admin' => [
                'en' => 'Department Admin',
                'ar' => 'مشرف قسم'
            ],
            'teacher' => [
                'en' => 'Teacher',
                'ar' => 'معلم'
            ],
            'supervisor' => [
                'en' => 'Supervisor',
                'ar' => 'مشرف'
            ],
            'student' => [
                'en' => 'Student',
                'ar' => 'طالب'
            ],
            'auth.failed' => [
                'en' => 'Authentication failed',
                'ar' => 'فشلت عملية تسجيل الدخول'
            ],
            'auth.throttle' => [
                'en' => 'Too many login attempts. Please wait before trying again.',
                'ar' => 'تجاوزت عدد محاولات تسجيل الدخول. يرجى الانتظار قبل المحاولة مرة أخرى.'
            ]
        ];
    }
} 