<?php

namespace Modules\Helpers\Database\Seeders;

use App\Models\People\Person;
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

        factory(Person::class, 50)->create()->each(function($person) use($responsibilities) {
            $helper = factory(Helper::class)->make();
            $person->helper()->save($helper);
            $helper->responsibilities()->sync($responsibilities->random(mt_rand(0, min(3, $responsibilities->count())))->pluck('id')->all());
        });

    }
}
