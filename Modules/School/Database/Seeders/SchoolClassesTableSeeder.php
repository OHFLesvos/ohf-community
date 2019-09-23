<?php

namespace Modules\School\Database\Seeders;

use Modules\People\Entities\Person;
use Modules\School\Entities\SchoolClass;

use Illuminate\Database\Seeder;

use Faker\Factory;

class SchoolClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $persons = factory(Person::class, 500)->create();
        factory(SchoolClass::class, 25)->create()->each(function($class) use($persons, $faker) {
            $ids = $persons->random(mt_rand(0, $class->capacity))->pluck('id')->mapWithKeys(function($e) use ($faker) {
                return [$e => [
                    'remarks' => $faker->optional(0.2)->sentence,
                ]];
            });
            $class->students()->sync($ids);
        });
    }
}
