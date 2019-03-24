<?php

namespace Modules\Fundraising\Database\Seeders;

use Modules\Fundraising\Entities\Donor;

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
        factory(Donor::class, 100)->create();
    }
}
