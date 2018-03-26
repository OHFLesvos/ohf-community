<?php

use Illuminate\Database\Seeder;
use App\CouponType;

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
