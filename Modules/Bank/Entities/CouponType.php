<?php

namespace Modules\Bank\Entities;

use Illuminate\Database\Eloquent\Model;

use Iatstuti\Database\Support\NullableFields;

class CouponType extends Model
{
    use NullableFields;

    protected $fillable = [
        'name',
        'icon',
        'daily_amount',
        'retention_period',
        'min_age',
        'max_age',
        'daily_spending_limit',
        'order',
        'returnable',
        'enabled'
    ];

    protected $nullable = [
		'icon',
		'retention_period',
		'min_age',
        'max_age',
        'daily_spending_limit',
    ];

    public function couponHandouts() {
        return $this->hasMany('Modules\Bank\Entities\CouponHandout');
    }

    public function CouponReturns() {
        return $this->hasMany('Modules\Bank\Entities\CouponReturn');
    }
}
