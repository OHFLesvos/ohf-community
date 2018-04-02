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
    ];

    public function couponType() {
        return $this->belongsTo('App\CouponType');
    }

    public function person() {
        return $this->belongsTo('App\Person');
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
