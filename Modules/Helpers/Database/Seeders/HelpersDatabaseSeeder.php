<?php

namespace Modules\Helpers\Database\Seeders;

use Modules\People\Entities\Person;
use Modules\Helpers\Entities\Helper;

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

        $faker = Factory::create();
        $persons = factory(Person::class, 50)->create()->each(function($person) use($faker) {
            $helper = factory(Helper::class)->make();
            $person->helper()->save($helper);
        });
        
        // factory(SchoolClass::class, 25)->create()->each(function($class) use($persons, $faker) {
        //     $ids = $persons->random(mt_rand(0, $class->capacity))->pluck('id')->mapWithKeys(function($e) use ($faker) {
        //         return [$e => [
        //             'remarks' => $faker->optional(0.2)->sentence,
        //         ]];
        //     });
        //     $class->students()->sync($ids);
        // });

    }
}
