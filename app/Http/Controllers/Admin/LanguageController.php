<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    /**
     * Display a listing of the languages.
     */
    public function index()
    {
        $languages = Language::orderBy('name')->get();
        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new language.
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created language in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:5|unique:languages,code',
            'direction' => 'required|in:ltr,rtl',
            'is_active' => 'sometimes|boolean',
        ]);

        // Set is_active default value
        $validated['is_active'] = $request->has('is_active');

        Language::create($validated);
        
        // Clear the cache
        $this->clearLanguageCache();

        return redirect()->route('admin.languages.index')
            ->with('success', t('Language created successfully'));
    }

    /**
     * Show the form for editing the specified language.
     */
    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    /**
     * Update the specified language in storage.
     */
    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => [
                'required', 
                'string', 
                'max:5',
                Rule::unique('languages')->ignore($language->id),
            ],
            'direction' => 'required|in:ltr,rtl',
            'is_active' => 'sometimes|boolean',
        ]);

        // Set is_active default value
        $validated['is_active'] = $request->has('is_active');

        $language->update($validated);
        
        // Clear the cache
        $this->clearLanguageCache();

        return redirect()->route('admin.languages.index')
            ->with('success', t('Language updated successfully'));
    }

    /**
     * Remove the specified language from storage.
     */
    public function destroy(Language $language)
    {
        // Don't allow deleting the default languages (en/ar)
        if (in_array($language->code, ['en', 'ar'])) {
            return redirect()->route('admin.languages.index')
                ->with('error', t('Cannot delete the default languages'));
        }

        // Check if this is the last active language
        $activeLanguages = Language::where('is_active', true)->count();
        if ($activeLanguages <= 1 && $language->is_active) {
            return redirect()->route('admin.languages.index')
                ->with('error', t('Cannot delete the only active language'));
        }

        $language->delete();
        
        // Clear the cache
        $this->clearLanguageCache();

        return redirect()->route('admin.languages.index')
            ->with('success', t('Language deleted successfully'));
    }

    /**
     * Toggle the status of the specified language.
     */
    public function toggleStatus(Language $language)
    {
        // Don't allow deactivating if this is the only active language
        if ($language->is_active) {
            $activeLanguages = Language::where('is_active', true)->count();
            if ($activeLanguages <= 1) {
                return redirect()->route('admin.languages.index')
                    ->with('error', t('Cannot deactivate the only active language'));
            }
        }

        $language->is_active = !$language->is_active;
        $language->save();
        
        // Clear the cache
        $this->clearLanguageCache();

        return redirect()->route('admin.languages.index')
            ->with('success', t('Language status updated successfully'));
    }

    /**
     * Clear language-related cache.
     */
    private function clearLanguageCache()
    {
        Cache::forget('active_languages');
        Cache::forget('all_languages');
    }
} 