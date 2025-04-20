<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\AppTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TranslationController extends Controller
{
    /**
     * Display a listing of the translations.
     */
    public function index(Request $request): View
    {
        $languages = Language::where('is_active', true)->get();
        $selectedLanguage = $request->input('language', $languages->first()->code ?? 'en');
        $searchQuery = $request->input('search');
        
        $query = AppTranslation::whereHas('language', function ($query) use ($selectedLanguage) {
            $query->where('code', $selectedLanguage);
        });
        
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('translation_key', 'like', "%{$searchQuery}%")
                  ->orWhere('translation_value', 'like', "%{$searchQuery}%");
            });
        }
        
        $translations = $query->orderBy('translation_key')->paginate(20);
        
        return view('admin.translations.index', compact('translations', 'languages', 'selectedLanguage', 'searchQuery'));
    }
    
    /**
     * Show the form for creating a new translation.
     */
    public function create(): View
    {
        $languages = Language::where('is_active', true)->get();
        return view('admin.translations.create', compact('languages'));
    }
    
    /**
     * Store a newly created translation in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'translation_key' => 'required|string|max:255',
            'translation_value' => 'required|string',
        ]);
        
        // Check for duplicate
        $exists = AppTranslation::where('language_id', $request->language_id)
            ->where('translation_key', $request->translation_key)
            ->exists();
            
        if ($exists) {
            return redirect()->back()->with('error', t('Translation key already exists for this language'));
        }
        
        // Create the translation
        AppTranslation::create($request->only([
            'language_id', 'translation_key', 'translation_value'
        ]));
        
        // Clear translation cache
        $this->clearTranslationCache($request->translation_key);
        
        return redirect()->route('admin.translations.index')
            ->with('success', t('Translation created successfully'));
    }
    
    /**
     * Show the form for editing the specified translation.
     */
    public function edit(AppTranslation $translation): View
    {
        $languages = Language::where('is_active', true)->get();
        return view('admin.translations.edit', compact('translation', 'languages'));
    }
    
    /**
     * Update the specified translation in storage.
     */
    public function update(Request $request, AppTranslation $translation): RedirectResponse
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'translation_key' => 'required|string|max:255',
            'translation_value' => 'required|string',
        ]);
        
        // Check for duplicate only if key or language changed
        if ($translation->translation_key != $request->translation_key || 
            $translation->language_id != $request->language_id) {
                
            $exists = AppTranslation::where('language_id', $request->language_id)
                ->where('translation_key', $request->translation_key)
                ->where('id', '!=', $translation->id)
                ->exists();
                
            if ($exists) {
                return redirect()->back()->with('error', t('Translation key already exists for this language'));
            }
        }
        
        // Remember old key for cache clearing
        $oldKey = $translation->translation_key;
        
        // Update the translation
        $translation->update($request->only([
            'language_id', 'translation_key', 'translation_value'
        ]));
        
        // Clear translation cache for both old and new keys
        $this->clearTranslationCache($oldKey);
        if ($oldKey !== $request->translation_key) {
            $this->clearTranslationCache($request->translation_key);
        }
        
        return redirect()->route('admin.translations.index')
            ->with('success', t('Translation updated successfully'));
    }
    
    /**
     * Remove the specified translation from storage.
     */
    public function destroy(AppTranslation $translation): RedirectResponse
    {
        // Remember key for cache clearing
        $key = $translation->translation_key;
        
        // Delete the translation
        $translation->delete();
        
        // Clear translation cache
        $this->clearTranslationCache($key);
        
        return redirect()->route('admin.translations.index')
            ->with('success', t('Translation deleted successfully'));
    }
    
    /**
     * Copy translation keys to another language.
     */
    public function copy(Request $request): RedirectResponse
    {
        $request->validate([
            'source_language' => 'required|exists:languages,code',
            'target_language' => 'required|exists:languages,code|different:source_language',
        ]);
        
        $sourceLanguage = Language::where('code', $request->source_language)->first();
        $targetLanguage = Language::where('code', $request->target_language)->first();
        
        if (!$sourceLanguage || !$targetLanguage) {
            return redirect()->back()->with('error', t('Invalid language selection'));
        }
        
        // Get all translations for source language
        $sourceTranslations = AppTranslation::where('language_id', $sourceLanguage->id)->get();
        
        $copied = 0;
        $skipped = 0;
        
        foreach ($sourceTranslations as $translation) {
            // Check if the key already exists in target language
            $exists = AppTranslation::where('language_id', $targetLanguage->id)
                ->where('translation_key', $translation->translation_key)
                ->exists();
                
            if (!$exists) {
                // Create new translation with same key but empty value
                AppTranslation::create([
                    'language_id' => $targetLanguage->id,
                    'translation_key' => $translation->translation_key,
                    'translation_value' => '', // Empty to encourage translation
                ]);
                $copied++;
            } else {
                $skipped++;
            }
        }
        
        // Clear all translation cache
        Cache::forget('translation_*');
        
        $message = sprintf(t('Copied %d keys, skipped %d existing keys'), $copied, $skipped);
        return redirect()->route('admin.translations.index', ['language' => $targetLanguage->code])
            ->with('success', $message);
    }
    
    /**
     * Import translations from a JSON file.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'import_file' => 'required|file|mimes:json',
        ]);
        
        $file = $request->file('import_file');
        $content = file_get_contents($file->getPathname());
        $translations = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', t('Invalid JSON file'));
        }
        
        $imported = 0;
        $updated = 0;
        
        foreach ($translations as $key => $value) {
            // Check if the key already exists
            $existing = AppTranslation::where('language_id', $request->language_id)
                ->where('translation_key', $key)
                ->first();
                
            if ($existing) {
                $existing->update(['translation_value' => $value]);
                $updated++;
            } else {
                AppTranslation::create([
                    'language_id' => $request->language_id,
                    'translation_key' => $key,
                    'translation_value' => $value,
                ]);
                $imported++;
            }
            
            // Clear cache for this key
            $this->clearTranslationCache($key);
        }
        
        $message = sprintf(t('Imported %d new keys, updated %d existing keys'), $imported, $updated);
        return redirect()->route('admin.translations.index')
            ->with('success', $message);
    }
    
    /**
     * Export translations to a JSON file.
     */
    public function export(Request $request)
    {
        $request->validate([
            'language' => 'required|exists:languages,code',
        ]);
        
        $language = Language::where('code', $request->language)->first();
        
        $translations = AppTranslation::whereHas('language', function ($query) use ($request) {
                $query->where('code', $request->language);
            })
            ->orderBy('translation_key')
            ->get()
            ->pluck('translation_value', 'translation_key')
            ->toArray();
            
        $jsonContent = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        $filename = "translations_{$request->language}.json";
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        return response()->make($jsonContent, 200, $headers);
    }
    
    /**
     * Generate language files from database entries.
     */
    public function generate(): RedirectResponse
    {
        try {
            // Run the command to generate language files from database
            Artisan::call('translations:generate');
            
            return redirect()->route('admin.translations.index')
                ->with('success', t('Translation files generated successfully'));
        } catch (\Exception $e) {
            return redirect()->route('admin.translations.index')
                ->with('error', t('Error generating translation files') . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Clear translation cache for a specific key.
     */
    private function clearTranslationCache(string $key): void
    {
        $languages = Language::all();
        foreach ($languages as $language) {
            Cache::forget("translation_{$language->code}_{$key}");
        }
    }
} 