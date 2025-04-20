<?php

namespace Database\Seeders\Translations;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class AbstractTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all language IDs
        $languages = Language::pluck('id', 'code')->toArray();
        
        // Get translations
        $translations = $this->getTranslations();
        
        // Prepare the translation records
        $translationRecords = [];
        
        foreach ($translations as $key => $values) {
            foreach ($values as $languageCode => $value) {
                if (isset($languages[$languageCode])) {
                    $translationRecords[] = [
                        'language_id' => $languages[$languageCode],
                        'translation_key' => $key,
                        'translation_value' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        // Insert translations in batches
        if (!empty($translationRecords)) {
            foreach (array_chunk($translationRecords, 100) as $chunk) {
                DB::table('translations')->insertOrIgnore($chunk);
            }
        }
    }

    /**
     * Get translations to be seeded
     *
     * @return array
     */
    abstract protected function getTranslations(): array;
} 