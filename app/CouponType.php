<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponType extends Model
{
    public function couponHandouts() {
        return $this->hasMany('App\CouponHandout');
    }
}
