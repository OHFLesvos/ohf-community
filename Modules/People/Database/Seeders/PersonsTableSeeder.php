<?php

namespace Modules\People\Database\Seeders;

use Modules\People\Entities\Person;

use Illuminate\Database\Seeder;

class PersonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Person::class, 1000)->create();
    }
}
