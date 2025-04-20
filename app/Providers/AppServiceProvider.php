<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Language;
use App\Models\AppTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register helpers file
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Make sure migrations have run before trying to access database tables
        if (Schema::hasTable('languages') && Schema::hasTable('translations')) {
            // Load translations from database
            $this->loadDatabaseTranslations();
        }
    }
    
    /**
     * Load translations from database into cache if needed
     */
    private function loadDatabaseTranslations(): void
    {
        // Warm up the translation cache for active languages if empty
        if (!Cache::has('active_languages')) {
            try {
                // Cache active languages
                $activeLanguages = Language::where('is_active', true)
                    ->orderBy('name')
                    ->pluck('code')
                    ->toArray();
                
                Cache::put('active_languages', $activeLanguages, now()->addDay());
                
                // Pre-cache common translations
                foreach ($activeLanguages as $locale) {
                    $translations = AppTranslation::whereHas('language', function($query) use ($locale) {
                            $query->where('code', $locale);
                        })
                        ->orderBy('translation_key')
                        ->get();
                        
                    foreach ($translations as $translation) {
                        $cacheKey = "translation_{$locale}_{$translation->translation_key}";
                        Cache::put($cacheKey, $translation->translation_value, now()->addDay());
                    }
                }
            } catch (\Exception $e) {
                // Fail silently during app initialization
                // Log error for debugging
                \Log::error('Translation initialization error: ' . $e->getMessage());
            }
        }
    }
}
