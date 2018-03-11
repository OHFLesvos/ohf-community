<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponHandout extends Model
{
    public static function boot()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });

        parent::boot();
    }

    public function couponType() {
        return $this->belongsTo('App\CouponType');
    }

    public function person() {
        return $this->belongsTo('App\Person');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
