<?php

namespace Modules\Bank\Entities;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CouponReturn extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'project_id',
        'coupon_type_id',
        'date',
        'amount',
    ];

    public function couponType() {
        return $this->belongsTo('Modules\Bank\Entities\CouponType');
    }

    public function project() {
        return $this->belongsTo('App\Project');
    }

    /**
     * {@inheritdoc}
     */
    public function generateTags(): array
    {
        return [
            $this->project->name,
            $this->couponType->name,
        ];
    }
}
