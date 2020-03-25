<?php

use App\Models\Helpers\Helper;
use App\Models\Helpers\Responsibility;
use App\Models\People\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class HelpersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $responsibilities = factory(Responsibility::class, 15)->create();

        factory(Person::class, 50)->create()->each(function ($person) use ($responsibilities) {
            $helper = factory(Helper::class)->make();
            $person->helper()->save($helper);
            $helper->responsibilities()->sync($responsibilities->random(mt_rand(0, min(3, $responsibilities->count())))->pluck('id')->all());
        });

    }
}
