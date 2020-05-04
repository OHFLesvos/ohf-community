<?php

namespace App\Models\Traits;

use App;
use Countries;

trait HasCountryCodeField
{
    /**
     * Get the country name based on the country code
     *
     * @return string
     */
    public function getCountryNameAttribute()
    {
        if ($this->country_code != null) {
            return Countries::getOne($this->country_code, App::getLocale());
        }
        return null;
    }

    /**
     * Set the country code based on the country name
     *
     * @param  string  $value
     * @return void
     */
    public function setCountryNameAttribute($value)
    {
        $this->attributes['country_code'] = $value != null
            ? array_flip(Countries::getList(App::getLocale()))[$value] ?? null
            : null;
    }

    public static function countryDistribution($untilDate = null): array
    {
        return self::select('country_code')
            ->selectRaw('COUNT(*) as countries_count')
            ->groupBy('country_code')
            ->whereNotNull('country_code')
            ->createdUntil($untilDate)
            ->orderBy('countries_count', 'desc')
            ->get()
            ->map(fn ($e) => [
                'name' => $e->countryName,
                'amount' => $e->countries_count,
            ])
            ->toArray();
    }
}
