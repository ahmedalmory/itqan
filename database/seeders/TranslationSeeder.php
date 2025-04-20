<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Translations\AppTranslationSeeder;
use Database\Seeders\Translations\AuthTranslationSeeder;
use Database\Seeders\Translations\CircleTranslationSeeder;
use Database\Seeders\Translations\DepartmentTranslationSeeder;
use Database\Seeders\Translations\NavigationTranslationSeeder;
use Database\Seeders\Translations\StudentTranslationSeeder;
use Database\Seeders\Translations\SupervisorTranslationSeeder;
use Database\Seeders\Translations\TeacherTranslationSeeder;
use Database\Seeders\Translations\UserTranslationSeeder;
use App\Models\Language;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get language IDs
        $arabicId = Language::where('code', 'ar')->first()->id;
        $englishId = Language::where('code', 'en')->first()->id;

        $this->call([
            AppTranslationSeeder::class,
            AuthTranslationSeeder::class,
            NavigationTranslationSeeder::class,
            UserTranslationSeeder::class,
            CircleTranslationSeeder::class,
            DepartmentTranslationSeeder::class,
            StudentTranslationSeeder::class,
            TeacherTranslationSeeder::class,
            SupervisorTranslationSeeder::class,
        ]);
        
        $this->command->info('All translation seeders have been run successfully!');
    }
}