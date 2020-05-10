<?php

use App\Models\Bank\CouponHandout;
use App\Models\Bank\CouponType;
use App\Models\People\Person;
use Illuminate\Database\Seeder;

class BankWithdrawalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $couponTypes = CouponType::all();

        Person::inRandomOrder()
            ->limit(1000)
            ->get()
            ->each(function (Person $person) use ($couponTypes) {
                $numCouponTypes = mt_rand(0, $couponTypes->count());
                $couponTypes->random($numCouponTypes)
                    ->each(function (CouponType $couponType) use($person) {
                        $handout = factory(CouponHandout::class)->make();
                        $handout->person()->associate($person);
                        $handout->couponType()->associate($couponType);
                        $handout->save();
                });
            });
    }
}
