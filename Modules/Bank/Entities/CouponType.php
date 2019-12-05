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
        'newly_registered_block_days',
        'order',
        'returnable',
        'enabled',
        'qr_code_enabled',
        'code_expiry_days',
        'allow_for_helpers'
    ];

    protected $nullable = [
		'icon',
		'retention_period',
		'min_age',
        'max_age',
        'daily_spending_limit',
        'code_expiry_days'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'returnable' => 'boolean',
        'qr_code_enabled' => 'boolean',
        'allow_for_helpers' => 'boolean',
    ];

    public function couponHandouts() {
        return $this->hasMany('Modules\Bank\Entities\CouponHandout');
    }

    public function CouponReturns() {
        return $this->hasMany('Modules\Bank\Entities\CouponReturn');
    }
}
