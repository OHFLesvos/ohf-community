<?php

use App\Project;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
                    'Boutique',
                    'Café',
                    'Shop',
                    'Shisha Lounge',
                    'Barber',
                    'Tailor'
                 ] as $name) {
            Project::create([
                'name' => $name,
                'enable_in_bank' => 1
            ]);
        }
    }
}
