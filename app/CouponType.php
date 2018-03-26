<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function couponHandouts() {
        return $this->hasMany('App\CouponHandout');
    }

    public function CouponReturns() {
        return $this->hasMany('App\CouponReturn');
    }
}
