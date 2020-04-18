<?php

namespace App\Models\Fundraising;

use App\Support\Traits\HasTags;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Donor extends Model
{
    use HasTags;
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
            return \Countries::getOne($this->country_code, \App::getLocale());
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
        $this->attributes['country_code'] = $value != null ? array_flip(\Countries::getList(\App::getLocale()))[$value] ?? null : null;
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function amountPerYear($year)
    {
        return $this->donations()
            ->forYear($year)
            ->select(DB::raw('sum(exchange_amount) as total'))
            ->get()
            ->pluck('total')
            ->first();
    }

    public function amountPerYearByCurrencies($year)
    {
        return $this->donations()
            ->forYear($year)
            ->select(DB::raw('sum(amount) as total'), 'currency')
            ->groupBy('currency')
            ->get()
            ->pluck('total', 'currency');
    }

    public function donationsPerYear()
    {
        return $this->donations()
            ->groupBy(DB::raw('YEAR(date)'))
            ->select(DB::raw('YEAR(date) as year'), DB::raw('sum(exchange_amount) as total'))
            ->get();
    }

    public function addDonation(Donation $donation)
    {
        $this->donations()->save($donation);
    }

    public function addDonations($donations)
    {
        $this->donations()->saveMany($donations);
    }

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

}
