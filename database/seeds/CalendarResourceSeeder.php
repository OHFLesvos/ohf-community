<?php

use App\CalendarResource;
use Illuminate\Database\Seeder;

class CalendarResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
            ['name' => 'School', 'default' => true],
            ['name' => 'Garden', 'color' => 'orange'],
            ['name' => 'Office', 'color' => 'red'],
            ['name' => 'Shop', 'color' => 'green'],
        ] as $t) {
            CalendarResource::create($t);
        }
    }
}
