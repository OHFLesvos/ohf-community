<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class CouponType extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'daily_amount',
        'retention_period',
        'min_age',
        'max_age',
        'order',
        'returnable',
        'enabled'
    ];

    protected $nullable = [
		'icon',
		'retention_period',
		'min_age',
		'max_age',
    ];

    public function couponHandouts() {
        return $this->hasMany('App\CouponHandout');
    }

    public function CouponReturns() {
        return $this->hasMany('App\CouponReturn');
    }
}
