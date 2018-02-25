<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Donor extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    function donations() {
        return $this->hasMany('App\Donation');
    }

    function amountPerYear($year, $currency = null) {
        $query = $this->donations()
            ->whereYear('date', $year)
            ->select(DB::raw('sum(amount) as total'), 'currency')
            ->groupBy('currency');
        if ($currency != null) {
            $query->where('currency', $currency);
            return $query->get()->first();
        } else {
            return $query->get();
        }
    }
}
