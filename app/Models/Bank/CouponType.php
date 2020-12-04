<?php

namespace App\Models\Bank;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $nullable = [
        'icon',
        'retention_period',
        'min_age',
        'max_age',
        'daily_spending_limit',
        'code_expiry_days',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'returnable' => 'boolean',
        'qr_code_enabled' => 'boolean',
    ];

    public function couponHandouts()
    {
        return $this->hasMany(CouponHandout::class);
    }
}
