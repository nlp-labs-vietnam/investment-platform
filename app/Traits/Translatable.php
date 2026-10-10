<?php

namespace App\Traits;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

trait Translatable
{
    public static function booted()
    {
        // Skip language scope for admin panel requests.
        // Note: config default guard is always 'web'; admin uses 'auth:admin' middleware
        // which sets the guard via shouldUse(). Check both the default driver and
        // whether an admin session is currently authenticated.
        if (Auth::getDefaultDriver() === 'admin' || Auth::guard('admin')->check()) {
            return; // Admin panel: no language scope, show all records
        }

        $lang = app()->getLocale();

        // Resolve language_id for the current locale.
        // Try exact locale match first, then default_status, then any active language.
        $languageId = Cache::remember("translatable_language_{$lang}", now()->addMinutes(60), function () use ($lang) {
            return Language::where('short_name', $lang)->value('id')
                ?? Language::where('default_status', 1)->value('id')
                ?? Language::where('status', 1)->value('id');
        });

        if (!$languageId) {
            return; // No language configured — do not scope
        }

        // Apply scope: prefer the resolved language_id.
        // Controllers that need a graceful fallback (e.g. FrontendController)
        // should query with ->withoutGlobalScope('language') as a secondary attempt.
        static::addGlobalScope('language', function (Builder $builder) use ($languageId) {
            $builder->where('language_id', $languageId);
        });
    }
}
