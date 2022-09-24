<?php

namespace App\Models\Traits;

use App;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Languages;

trait LanguageCodeField
{
    /**
     * Gets/sets the language name based on the language code
     *
     * @return Attribute<?string,?string>
     */
    protected function language(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->language_code === null ? null : Languages::lookup([$this->language_code], App::getLocale())->first(),
            set: fn ($value) => [
                'language_code' => $value === null ? null : localized_language_names()
                    ->map(fn ($l) => strtolower($l))
                    ->flip()
                    ->get(strtolower($value)),
            ],
        );
    }

    /**
     * Gets a sorted list of all languages used by the model type.
     */
    public static function languages(): array
    {
        return self::select('language_code')
            ->distinct()
            ->whereNotNull('language_code')
            ->orderBy('language_code')
            ->get()
            ->map(fn ($e) => $e->language)
            ->toArray();
    }

    /**
     * Gets an array of all languages assigned to records, grouped and ordered by amount
     */
    public static function languageDistribution(string|\Carbon\Carbon|null $untilDate = null): array
    {
        return self::select('language_code')
            ->selectRaw('COUNT(*) as languages_count')
            ->groupBy('language_code')
            ->whereNotNull('language_code')
            ->createdUntil($untilDate)
            ->orderBy('languages_count', 'desc')
            ->get()
            ->map(fn ($e) => [
                'name' => $e['language'],
                'amount' => $e['languages_count'],
            ])
            ->toArray();
    }
}
