<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Iatstuti\Database\Support\NullableFields;

class CouponHandout extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use NullableFields;

    protected $fillable = [
        'date',
        'amount',
        'person_id',
        'coupon_type_id',
    ];

    protected $nullable = [
        'code',
        'code_redeemed',
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
