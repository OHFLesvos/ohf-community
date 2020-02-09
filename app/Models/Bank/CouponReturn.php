<?php

namespace App\Models\Bank;

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

    public function couponType()
    {
        return $this->belongsTo(CouponType::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
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
