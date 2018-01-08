<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
                     'Coordinator',
                     'Volunteer',
                     'Helper',
                     'Partner',
                 ] as $name) {
            \App\Role::create([
                'name' => $name,
            ]);
        }

    }
}
