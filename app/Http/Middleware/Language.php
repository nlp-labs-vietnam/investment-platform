<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language as LanguageModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Language
{
    public function handle($request, Closure $next)
    {
        if (request()->has('theme')) {
            $allowedThemes = ['light', 'green'];
            $requestedTheme = request()->query('theme');
            if (in_array($requestedTheme, $allowedThemes)) {
                session()->put('active_theme', $requestedTheme);
            }
        }

        try {
            DB::connection()->getPdo();
            $defaultLanguage = $this->getDefaultLanguage();

            // Respect user's chosen language stored in session;
            // only fall back to site default when no session choice exists.
            if (!session()->has('lang')) {
                $langCode = $defaultLanguage ? $defaultLanguage->short_name : 'en';
                $rtl = $defaultLanguage ? $defaultLanguage->rtl : 0;
                session()->put('lang', $langCode);
                session()->put('rtl', $rtl);
            } else {
                $langCode = session('lang');
                // Sync RTL for the chosen language if not yet set
                if (!session()->has('rtl')) {
                    $languages = $this->getActiveLanguages();
                    $chosen = $languages->firstWhere('short_name', $langCode);
                    session()->put('rtl', $chosen ? $chosen->rtl : 0);
                }
            }

            app()->setLocale($langCode);
            return $next($request);
        } catch (\Exception $exception) {
            return $next($request);
        }
    }

    public function getCode($defaultLanguage = null)
    {
        return session('lang', $defaultLanguage ? $defaultLanguage->short_name : 'en');
    }

    public function getDirection($defaultLanguage = null)
    {
        return session('rtl', $defaultLanguage ? $defaultLanguage->rtl : 0);
    }

    public function getDefaultLanguage()
    {
        return Cache::remember('default_language', now()->addHour(), function () {
            return LanguageModel::where('status', 1)
                ->where(function ($query) {
                    $query->where('default_status', 1)
                        ->orWhere('default_status', 0);
                })
                ->orderBy('default_status', 'desc')
                ->first();
        });
    }

    public function getActiveLanguages()
    {
        return Cache::remember('active_languages', now()->addMinutes(60), function () {
            return LanguageModel::where('status', 1)
                ->orderBy('default_status', 'desc')
                ->get();
        });
    }
}
