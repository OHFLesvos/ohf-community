<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    function donor() {
        return $this->belongsTo('App\Donor');
    }

    static function currenciesPerYear($year) {
        return Donation::whereYear('date', $year)
            ->select('currency')
            ->groupBy('currency')
            ->get()
            ->pluck('currency');
    }
}
