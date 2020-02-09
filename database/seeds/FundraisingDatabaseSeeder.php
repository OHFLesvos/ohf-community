<?php

use App\Models\Fundraising\Donor;
use App\Models\Fundraising\Donation;

use Illuminate\Database\Seeder;

class DonorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Donor::class, 250)->create()->each(function($d){
            $d->donations()->saveMany(factory(Donation::class, mt_rand(1, 10))->make());
        });
    }
}
