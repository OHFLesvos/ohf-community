<?php

namespace App\Models\Fundraising;

use App\Models\Traits\CommentsRelation;
use App\Models\Traits\CountryCodeField;
use App\Models\Traits\CreatedUntilScope;
use App\Models\Traits\InDateRangeScope;
use App\Models\Traits\LanguageCodeField;
use App\Models\Traits\TagsRelation;
use App\Models\Tag;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Donor extends Model
{
    use HasFactory;
    use TagsRelation;
    use CommentsRelation;
    use LanguageCodeField;
    use CountryCodeField;
    use InDateRangeScope;
    use CreatedUntilScope;
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
        'language_code',
    ];

    protected $fillable = [
        'salutation',
        'first_name',
        'last_name',
        'company',
        'street',
        'zip',
        'city',
        'country_name',
        'country_code',
        'email',
        'phone',
        'language',
        'language_code',
    ];

    public static function boot()
    {
        static::deleting(function ($model) {
            $model->tags()->detach();
        });
        parent::boot();
    }

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

    public function getFullAddressAttribute()
    {
        $str = '';
        if (isset($this->street)) {
            $str .= $this->street;
            $str .= "\n";
        }
        if (isset($this->zip)) {
            $str .= $this->zip;
            $str .= ' ';
        }
        if (isset($this->city)) {
            $str .= $this->city;
        }
        if (isset($this->zip) || isset($this->city)) {
            $str .= "\n";
        }
        if (isset($this->country_name)) {
            $str .= $this->country_name;
        }
        return trim($str);
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

    public function amountByChannelCurrencyYear(): Collection
    {
        return $this->donations()
            ->select('channel', 'currency')
            ->selectRaw('SUM(amount) AS total')
            ->selectRaw('YEAR(date) AS year')
            ->groupBy('channel')
            ->groupBy('currency')
            ->groupBy('year')
            ->get();
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
     * @param array|Collection $donations
     */
    public function addDonations($donations)
    {
        $this->donations()->saveMany($donations);
    }

    /**
     * Scope a query to only include donors matching the given filter
     * If no filter is specified, all records will be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string|null $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, ?string $filter = '')
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                $countries = localized_country_names()
                    ->map(fn ($name) => strtolower($name))
                    ->flip();
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
                    ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(REPLACE(phone, ' ', ''), '+', ''), '(', ''), ')', '')"), 'LIKE', '%' . str_replace(['+', '(', ')', ' '], '', $filter) . '%');
            });
        }

        return $query;
    }

    /**
     * Scope a query to only include donors matching the given filter
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSimpleFilter($query, string $filter)
    {
        return $query->where(DB::raw('CONCAT(first_name, \' \', last_name)'), 'LIKE', '%' . $filter . '%')
            ->orWhere(DB::raw('CONCAT(last_name, \' \', first_name)'), 'LIKE', '%' . $filter . '%')
            ->orWhere('company', 'LIKE', '%' . $filter . '%')
            ->orWhere('first_name', 'LIKE', '%' . $filter . '%')
            ->orWhere('last_name', 'LIKE', '%' . $filter . '%');
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
}
