<?php

namespace App\Console\Commands;

use App\Models\Language;
use App\Models\AppTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate language files from database translations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating translation files...');

        // Get all active languages
        $languages = Language::where('is_active', true)->get();
        
        if ($languages->isEmpty()) {
            $this->error('No active languages found');
            return Command::FAILURE;
        }

        $langPath = lang_path();
        $this->info("Using language path: {$langPath}");

        // Create language directories if they don't exist
        foreach ($languages as $language) {
            $languagePath = $langPath . '/' . $language->code;
            
            if (!File::exists($languagePath)) {
                File::makeDirectory($languagePath, 0755, true);
                $this->info("Created directory for {$language->code}");
            }
        }

        // Generate JSON translations for all languages
        foreach ($languages as $language) {
            $translations = AppTranslation::where('language_id', $language->id)
                ->orderBy('translation_key')
                ->get()
                ->pluck('translation_value', 'translation_key')
                ->toArray();
            
            $jsonContent = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $jsonFilePath = $langPath . '/' . $language->code . '.json';
            
            File::put($jsonFilePath, $jsonContent);
            $this->info("Generated {$language->code}.json with " . count($translations) . " translations");
        }

        $this->info('Translation files generated successfully');
        return Command::SUCCESS;
    }
} 