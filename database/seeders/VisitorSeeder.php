<?php

namespace Database\Seeders;

use App\Models\Visitors\Visitor;
use App\Models\Visitors\VisitorCheckin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('visitors')->delete();
        $visitors = Visitor::factory()
            ->count(1000)
            ->create();
        $visitors->each(function(Visitor $visitor) {
            $checkins = VisitorCheckin::factory()
                ->count(mt_rand(1, 5))
                ->make();
            $visitor->checkins()->saveMany($checkins->unique('checkin_date'));
        });
    }
}
