<?php

use App\Models\Bank\Project;

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
                    'Café',
                    'NFI Shop',
                    'Shisha Lounge',
                    'Barber Shop',
                    'Cyber Cafe',
                    'Makerspace',
                 ] as $name) {
            Project::create([
                'name' => $name,
                'enable_in_bank' => 1
            ]);
        }
    }
}
