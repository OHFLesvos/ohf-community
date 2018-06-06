<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Iatstuti\Database\Support\NullableFields;

class Donor extends Model
{
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

    function getFullNameAttribute() {
        $str = '';
        if ($this->first_name != null) {
            $str .= $this->first_name;
        }
        if ($this->last_name != null) {
            $str .= ' ' . $this->last_name;
        }
        if ($this->company != null) {
            if (!empty($str)) {
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
    public function getCountryNameAttribute() {
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

    function donations() {
        return $this->hasMany('App\Donation');
    }

    function amountPerYear($year) {
        return $this->donations()
            ->whereYear('date', $year)
            ->select(DB::raw('sum(exchange_amount) as total'))
            ->get()
            ->pluck('total')
            ->first();
    }

    function donationsPerYear() {
        return $this->donations()
            ->groupBy(DB::raw('YEAR(date)'))
            ->select(DB::raw('YEAR(date) as year'), DB::raw('sum(exchange_amount) as total'))
            ->get();
    }

    public static function languages() {
        return self::select('language')->groupBy('language')->whereNotNull('language')->orderBy('language')->get()->pluck('language')->toArray();
    }

    public static function salutations() {
        return self::select('salutation')->groupBy('salutation')->whereNotNull('salutation')->orderBy('salutation')->get()->pluck('salutation')->toArray();
    }
    
}
