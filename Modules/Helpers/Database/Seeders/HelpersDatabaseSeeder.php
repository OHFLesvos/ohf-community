<?php

namespace Modules\Helpers\Database\Seeders;

use Modules\People\Entities\Person;
use Modules\Helpers\Entities\Helper;
use Modules\Helpers\Entities\Responsibility;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory;

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

        factory(Person::class, 50)->create()->each(function($person) {
            $helper = factory(Helper::class)->make();
            $person->helper()->save($helper);
        });

    }
}
