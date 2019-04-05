<?php

namespace Modules\Bank\Database\Seeders;

use Modules\Bank\Entities\CouponType;

use Illuminate\Database\Seeder;

class CouponTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CouponType::create([
            'name' => 'Drachma',
            'icon' => 'money',
            'daily_amount' => 2,
            'retention_period' => 1,
            'min_age' => 12,
            'order' => 0,
        ]);
        CouponType::create([
            'name' => 'Boutique Coupon',
            'icon' => 'shopping-bag',
            'daily_amount' => 1,
            'retention_period' => 12,
            'min_age' => 15,
            'order' => 2,
        ]);
        CouponType::create([
            'name' => 'Diapers Coupon',
            'icon' => 'child',
            'daily_amount' => 1,
            'retention_period' => 1,
            'max_age' => 4,
            'order' => 3,
        ]);
        CouponType::create([
            'name' => 'Kids Drachma',
            'icon' => 'money',
            'daily_amount' => 1,
            'retention_period' => 1,
            'max_age' => 11,
            'order' => 1,
        ]);
        CouponType::create([
            'name' => 'Powerbank',
            'icon' => 'battery',
            'daily_amount' => 1,
            'retention_period' => null,
            'min_age' => 15,
            'order' => 4,
            'returnable' => false,
        ]);        
    }
}
