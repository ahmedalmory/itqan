<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get active languages from cache or database
        $activeLanguages = $this->getActiveLanguageCodes();
        
        $defaultLocale = 'ar';
        
        // Check session
        if (session()->has('locale') && in_array(session('locale'), $activeLanguages)) {
            app()->setLocale(session('locale'));
        } else {
            app()->setLocale($defaultLocale);
            session()->put('locale', $defaultLocale);
        }

        return $next($request);
    }
    
    /**
     * Get active language codes from cache or database.
     * 
     * @return array
     */
    private function getActiveLanguageCodes(): array
    {
        return Cache::remember('active_languages', now()->addDay(), function () {
            return Language::where('is_active', true)
                ->orderBy('name')
                ->pluck('code')
                ->toArray();
        });
    }
} 