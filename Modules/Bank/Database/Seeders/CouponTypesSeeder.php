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
            'icon' => 'money-bill-alt',
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
            'icon' => 'money-bill-alt',
            'daily_amount' => 1,
            'retention_period' => 1,
            'max_age' => 11,
            'order' => 1,
        ]);
        CouponType::create([
            'name' => 'Powerbank',
            'icon' => 'battery-full',
            'daily_amount' => 1,
            'retention_period' => null,
            'min_age' => 15,
            'order' => 4,
            'returnable' => false,
        ]);
        CouponType::create([
            'name' => 'Shop Coupon',
            'icon' => 'shopping-basket',
            'daily_amount' => 1,
            'retention_period' => 14,
            'min_age' => 15,
            'daily_spending_limit' => 25,
            'newly_registered_block_days' => 7,
            'order' => 5,
            'returnable' => false,
            'qr_code_enabled' => true,
        ]);
        CouponType::create([
            'name' => 'Barber ticket',
            'icon' => 'hand-scissors',
            'daily_amount' => 1,
            'retention_period' => 30,
            'daily_spending_limit' => 10,
            'order' => 6,
            'returnable' => false,
            'allow_for_helpers' => true,
        ]);
    }
}
