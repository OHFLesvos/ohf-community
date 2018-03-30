<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class CouponReturn extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public static function boot()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });

        parent::boot();
    }

    protected $fillable = [
        'project_id',
        'coupon_type_id',
        'date',
        'amount',
        'user_id',
    ];

    public function couponType() {
        return $this->belongsTo('App\CouponType');
    }

    public function project() {
        return $this->belongsTo('App\Project');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
