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
    public static function languages(): array
    {
        return self::select('language_code')
            ->distinct()
            ->whereNotNull('language_code')
            ->orderBy('language_code')
            ->get()
            ->pluck('language_code')
            ->map(fn ($lc) => Languages::lookup([$lc], App::getLocale())->first())
            ->toArray();
    }

    public static function languageDistribution(): array
    {
        return self::select('language_code')
            ->selectRaw('COUNT(*) as amount')
            ->groupBy('language_code')
            ->whereNotNull('language_code')
            ->orderBy('amount', 'desc')
            ->get()
            ->mapWithKeys(fn ($e) => [ $e->language => $e->amount ])
            ->toArray();
    }
}
