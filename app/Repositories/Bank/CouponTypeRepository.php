<?php

namespace App\Repositories\Bank;

use App\Models\Bank\CouponType;

class CouponTypeRepository
{
    public function getEnabled()
    {
        return CouponType::where('enabled', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    }
}
