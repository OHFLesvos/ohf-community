<?php

namespace Modules\Bank\Repositories;

use Modules\Bank\Entities\CouponType;

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