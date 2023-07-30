<?php

namespace App\Models\Traits;

use App;
use Carbon\Carbon;
use Countries;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait CountryCodeField
{
    /**
     * Get/set the country name based on the country code
     *
     * @return Attribute<?string,never>
     */
    protected function countryName(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->country_code !== null
                    ? Countries::getOne($this->country_code, App::getLocale())
                    : null;
            },
            set: fn (?string $value) => [
                'country_code' => $value !== null
                    ? localized_country_names()->flip()[$value] ?? null
                    : null,
            ],
        );
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
            ->map(fn (self $e) => $e->country_name)
            ->toArray();
    }

    /**
     * Gets an array of all countries assigned to model records, ordered by amount per country
     */
    public static function countryDistribution(string|Carbon $untilDate = null): array
    {
        return self::select('country_code')
            ->selectRaw('COUNT(*) as countries_count')
            ->groupBy('country_code')
            ->whereNotNull('country_code')
            ->createdUntil($untilDate)
            ->orderBy('countries_count', 'desc')
            ->get()
            ->map(fn (self $e) => [
                'name' => $e->country_name,
                'amount' => $e['countries_count'],
            ])
            ->toArray();
    }
}
