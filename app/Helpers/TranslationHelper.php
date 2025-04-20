<?php

namespace App\Helpers;

use App\Models\AppTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class TranslationHelper
{
    /**
     * Cache expiration time in hours
     */
    private const CACHE_EXPIRATION = 24;
    
    /**
     * Get a translation by key
     *
     * @param string $key The translation key
     * @param string|null $default Default text if translation not found
     * @return string
     */
    public static function get($key, $default = null)
    {
        $locale = app()->getLocale();
        
        // Try to get from cache first
        $cacheKey = "translation_{$locale}_{$key}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        // If not in cache, get from database
        $translation = AppTranslation::getTranslation($key, $locale, $default ?: $key);
        
        // Cache for configured time
        Cache::put($cacheKey, $translation, now()->addHours(self::CACHE_EXPIRATION));
        
        return $translation;
    }
    
    /**
     * Get a translation with parameters
     *
     * @param string $key The translation key
     * @param array $params Parameters to replace in the translation
     * @param string|null $default Default text if translation not found
     * @return string
     */
    public static function getWithParams($key, array $params = [], $default = null)
    {
        $translation = self::get($key, $default);
        
        foreach ($params as $paramKey => $paramValue) {
            $translation = str_replace(":{$paramKey}", $paramValue, $translation);
        }
        
        return $translation;
    }
    
    /**
     * Get all active languages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveLanguages()
    {
        return Cache::remember('active_languages', now()->addHours(self::CACHE_EXPIRATION), function () {
            return Language::where('is_active', true)->orderBy('name')->get();
        });
    }
    
    /**
     * Get all languages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllLanguages()
    {
        return Cache::remember('all_languages', now()->addHours(self::CACHE_EXPIRATION), function () {
            return Language::orderBy('name')->get();
        });
    }
    
    /**
     * Clear translation cache
     *
     * @param string|null $key Optional specific translation key to clear
     * @param string|null $locale Optional specific locale to clear
     * @return void
     */
    public static function clearCache($key = null, $locale = null)
    {
        if ($key && $locale) {
            Cache::forget("translation_{$locale}_{$key}");
        } elseif ($key) {
            // Get all languages from database to avoid circular dependency
            $languages = Language::pluck('code')->toArray();
            foreach ($languages as $loc) {
                Cache::forget("translation_{$loc}_{$key}");
            }
        } elseif ($locale) {
            Cache::forget("translation_{$locale}_*");
        } else {
            // Clear all translation caches
            Cache::forget('translation_*');
            // Also clear language caches
            Cache::forget('active_languages');
            Cache::forget('all_languages');
        }
    }
} 