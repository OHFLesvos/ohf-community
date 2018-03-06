<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Iatstuti\Database\Support\NullableFields;

class Donor extends Model
{
    use SoftDeletes;
    use NullableFields;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $nullable = [
		'address',
        'zip',
        'city',
        'country',
        'email',
        'phone',
        'remarks',
    ];

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
}
