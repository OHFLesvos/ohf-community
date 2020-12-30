<?php

namespace Database\Seeders;

use App\Models\Visitors\Visitor;
use Illuminate\Database\Seeder;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Visitor::factory()
            ->count(1000)
            ->create();
    }
}
