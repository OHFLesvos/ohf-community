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
            ['title' => 'School', 'default' => true],
            ['title' => 'Garden', 'color' => 'orange'],
            ['title' => 'Office', 'color' => 'red'],
            ['title' => 'Shop', 'color' => 'green'],
        ] as $t) {
            CalendarResource::create($t);
        }
    }
}
