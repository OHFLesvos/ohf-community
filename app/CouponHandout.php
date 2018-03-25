<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CouponHandout extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'person_id',
        'coupon_type_id',
        'user_id',
    ];

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
