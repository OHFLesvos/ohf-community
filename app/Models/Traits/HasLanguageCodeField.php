<?php

namespace App\Models\Traits;

use App;
use Languages;

trait HasLanguageCodeField
{
    public function setLanguageAttribute($value)
    {
        if ($value !== null) {
            $this->language_code = Languages::lookup(null, App::getLocale())
                ->map(fn ($l) => strtolower($l))
                ->flip()
                ->get(strtolower($value));
        } else {
            $this->language_code = null;
        }
    }

    public function getLanguageAttribute()
    {
        if ($this->language_code !== null) {
            return Languages::lookup([$this->language_code], App::getLocale())->first();
        }
        return null;
    }

    /**
     * Gets a sorted list of all languages used by the model type.
     *
     * @return array
     */
    public static function languages($untilDate = null): array
    {
        return self::select('language_code')
            ->distinct()
            ->whereNotNull('language_code')
            ->createdUntil($untilDate)
            ->orderBy('language_code')
            ->get()
            ->pluck('language_code')
            ->map(fn ($lc) => Languages::lookup([$lc], App::getLocale())->first())
            ->toArray();
    }

    public static function languageDistribution($untilDate = null): array
    {
        return self::select('language_code')
            ->selectRaw('COUNT(*) as languages_count')
            ->groupBy('language_code')
            ->whereNotNull('language_code')
            ->createdUntil($untilDate)
            ->orderBy('languages_count', 'desc')
            ->get()
            ->map(fn ($e) => [
                'name' => $e->language,
                'amount' => $e->languages_count,
            ])
            ->toArray();
    }
}
