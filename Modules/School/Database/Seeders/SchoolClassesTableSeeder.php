<?php

namespace Modules\School\Database\Seeders;

use Modules\People\Entities\Person;
use Modules\School\Entities\SchoolClass;

use Illuminate\Database\Seeder;

class SchoolClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $persons = factory(Person::class, 500)->create();
        factory(SchoolClass::class, 25)->create()->each(function($class) use($persons) {
            $class->students()->sync($persons->random(mt_rand(0, $class->capacity)));
        });
    }
}
