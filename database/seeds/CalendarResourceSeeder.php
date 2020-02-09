<?php

use App\Models\Collaboration\CalendarResource;

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
        factory(CalendarResource::class, 1)->states('default')->create();
        factory(CalendarResource::class, 10)->create();
    }
}
