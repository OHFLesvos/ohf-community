<?php

namespace Modules\School\Database\Seeders;

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
        factory(SchoolClass::class, 25)->create();
    }
}
