<?php

namespace App\Models\Fundraising;

use App\Models\HasComments;
use App\Support\Traits\HasTags;
use App\Tag;
use Countries;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Donor extends Model
{
    use HasTags;
    use HasComments;
    use NullableFields;

    protected $nullable = [
        'salutation',
        'first_name',
        'last_name',
        'company',
        'street',
        'zip',
        'city',
        'country',
        'email',
        'phone',
        'language',
        'remarks',
    ];

    public function getFullNameAttribute()
    {
        $str = '';
        if ($this->first_name != null) {
            $str .= $this->first_name;
        }
        if ($this->last_name != null) {
            $str .= ' ' . $this->last_name;
        }
        if ($this->company != null) {
            if (! empty($str)) {
                $str .= ', ';
            }
            $str .= $this->company;
        }
        return trim($str);
    }

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

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function amountPerYear(int $year): ?int
    {
        return $this->donations()
            ->selectRaw('SUM(exchange_amount) AS total')
            ->forYear($year)
            ->get()
            ->pluck('total')
            ->first();
    }

    public function amountPerYearByCurrencies(int $year): array
    {
        return $this->donations()
            ->select('currency')
            ->selectRaw('SUM(amount) AS total')
            ->forYear($year)
            ->groupBy('currency')
            ->get()
            ->pluck('total', 'currency')
            ->toArray();
    }

    /**
     * Gets a collection of donations per year
     *
     * @return Collection
     */
    public function donationsPerYear(): Collection
    {
        return $this->donations()
            ->selectRaw('YEAR(date) AS year')
            ->selectRaw('SUM(exchange_amount) AS total')
            ->groupBy('year')
            ->get();
    }

    /**
     * Adds the given donation
     *
     * @param Donation $donation
     */
    public function addDonation(Donation $donation)
    {
        $this->donations()->save($donation);
    }

    /**
     * Adds the given donations
     *
     * @param [type] $donations
     */
    public function addDonations($donations)
    {
        $this->donations()->saveMany($donations);
    }

    /**
     * Scope a query to only include donors having the given tags.
     * If no tags are specified, all records will be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param null|array<string> $tags list of tags (slug values)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithTags($query, ?array $tags = [])
    {
        if (count($tags) > 0) {
            $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('slug', $tags);
            });
        }
        return $query;
    }

    /**
     * Scope a query to only include donors matching the given filter
     * If no filter is specified, all records will be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param null|string $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, ?string $filter = '')
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                $countries = Countries::getList(App::getLocale());
                array_walk($countries, function (&$value, $idx) {
                    $value = strtolower($value);
                });
                $countries = array_flip($countries);
                return $wq->where(DB::raw('CONCAT(first_name, \' \', last_name)'), 'LIKE', '%' . $filter . '%')
                    ->orWhere(DB::raw('CONCAT(last_name, \' \', first_name)'), 'LIKE', '%' . $filter . '%')
                    ->orWhere('company', 'LIKE', '%' . $filter . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('street', 'LIKE', '%' . $filter . '%')
                    ->orWhere('zip', $filter)
                    ->orWhere('city', 'LIKE', '%' . $filter . '%')
                    ->orWhere(DB::raw('CONCAT(street, \' \', city)'), 'LIKE', '%' . $filter . '%')
                    ->orWhere(DB::raw('CONCAT(street, \' \', zip, \' \', city)'), 'LIKE', '%' . $filter . '%')
                    // Note: Countries filter only works for complete country code or country name
                    ->orWhere('country_code', $countries[strtolower($filter)] ?? $filter)
                    ->orWhere('email', 'LIKE', '%' . $filter . '%')
                    ->orWhere('phone', 'LIKE', '%' . $filter . '%');
            });
        }

        return $query;
    }

    /**
     * Gets a sorted list of all languages used by donors
     *
     * @return array
     */
    public static function languages(): array
    {
        return self::select('language')
            ->distinct()
            ->whereNotNull('language')
            ->orderBy('language')
            ->get()
            ->pluck('language')
            ->toArray();
    }

    /**
     * Gets a sorted list of all salutations used by donors
     *
     * @return array
     */
    public static function salutations(): array
    {
        return self::select('salutation')
            ->distinct()
            ->whereNotNull('salutation')
            ->orderBy('salutation')
            ->get()
            ->pluck('salutation')
            ->toArray();
    }

    public static function tagNames(): array
    {
        return Tag::has('donors')
            ->orderBy('name')
            ->get()
            ->pluck('name')
            ->toArray();
    }

    public static function tagMap(): array
    {
        return Tag::has('donors')
            ->orderBy('name')
            ->get()
            ->pluck('name', 'slug')
            ->toArray();
    }
}
