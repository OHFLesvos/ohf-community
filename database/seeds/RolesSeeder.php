<?php

use Illuminate\Database\Seeder;

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
                     'Banker',
                     'Donor',
                 ] as $name) {
            \App\Role::create([
                'name' => $name,
            ]);
        }
    }
}
