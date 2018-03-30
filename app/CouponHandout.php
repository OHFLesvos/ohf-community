<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class CouponHandout extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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

    /**
     * {@inheritdoc}
     */
    public function generateTags(): array
    {
        return [
            $this->couponType->name,
        ];
    }
}
