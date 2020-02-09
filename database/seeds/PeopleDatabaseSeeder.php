<?php

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
        factory(Person::class, 5000)->create();
    }
}
