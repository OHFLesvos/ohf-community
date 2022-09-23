<?php

namespace App\Models\Traits;

use App;
use Carbon\Carbon;
use Countries;

trait CountryCodeField
{
    /**
     * Get the country name based on the country code
     */
    public function getCountryNameAttribute(): ?string
    {
        if ($this->country_code != null) {
            return Countries::getOne($this->country_code, App::getLocale());
        }

        return null;
    }

    /**
     * Set the country code based on the country name
     */
    public function setCountryNameAttribute(?string $value): void
    {
        $this->attributes['country_code'] = $value != null
            ? localized_country_names()->flip()[$value] ?? null
            : null;
    }

    /**
     * Gets a sorted list of all countries used by the model type.
     */
    public static function countries(): array
    {
        return self::select('country_code')
            ->distinct()
            ->whereNotNull('country_code')
            ->orderBy('country_code')
            ->get()
            ->map(fn ($e) => $e->countryName)
            ->toArray();
    }

    /**
     * Gets an array of all countries assigned to records, grouped and ordered by amount
     */
    public static function countryDistribution(string|Carbon|null $untilDate = null): array
    {
        return self::select('country_code')
            ->selectRaw('COUNT(*) as countries_count')
            ->groupBy('country_code')
            ->whereNotNull('country_code')
            ->createdUntil($untilDate)
            ->orderBy('countries_count', 'desc')
            ->get()
            ->map(fn ($e) => [
                'name' => $e['countryName'],
                'amount' => $e['countries_count'],
            ])
            ->toArray();
    }
}
