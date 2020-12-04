<?php

namespace Database\Seeders;

use App\Models\People\Person;
use Illuminate\Database\Seeder;

class PeopleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Person::factory()
            ->count(5000)
            ->create();
    }
}
