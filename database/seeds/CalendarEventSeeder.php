<?php

use App\CalendarEvent;
use App\CalendarEventType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
            ['title' => 'A sample event', 'start_date' => Carbon::now()->addHours(1), 'end_date' => Carbon::now()->addHours(3), 'type_id' => rand(1,4), 'user_id' => 1],
        ] as $e) {
            CalendarEvent::create($e);
        }
    }
}
