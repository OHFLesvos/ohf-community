<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PersonsTableSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(ProjectsSeeder::class);
        $this->call(CalendarEventSeeder::class);
    }
}
