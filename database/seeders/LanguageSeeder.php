<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing languages
        Language::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Seed initial languages
        $languages = [
            [
                'name' => 'العربية',
                'code' => 'ar',
                'direction' => 'rtl',
                'is_active' => true,
            ],
            [
                'name' => 'English',
                'code' => 'en',
                'direction' => 'ltr',
                'is_active' => true,
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}