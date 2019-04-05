<?php

namespace Modules\Bank\Entities;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo('Modules\Bank\Entities\CouponType');
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
