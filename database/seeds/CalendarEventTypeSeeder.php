<?php

use App\CalendarEventType;
use Illuminate\Database\Seeder;

class CalendarEventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
            ['name' => 'School', 'color' => null],
            ['name' => 'Garden', 'color' => 'orange'],
            ['name' => 'Office', 'color' => 'red'],
            ['name' => 'Shop', 'color' => 'green'],
        ] as $t) {
            CalendarEventType::create($t);
        }
    }
}
