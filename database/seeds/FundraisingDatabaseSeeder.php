<?php

use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Illuminate\Database\Seeder;

class FundraisingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Donor::class, 250)->create()->each(function (Donor $donor) {
            $donor->addDonations(factory(Donation::class, mt_rand(1, 10))->make());
        });
    }
}
