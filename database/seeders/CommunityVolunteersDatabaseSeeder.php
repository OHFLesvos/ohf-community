<?php

namespace Database\Seeders;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class CommunityVolunteersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $responsibilities = Responsibility::factory()
            ->count(15)
            ->create();

        CommunityVolunteer::factory()
            ->count(50)
            ->create()
            ->each(function (CommunityVolunteer $cmtyvol) use ($responsibilities) {
                $ids = $responsibilities->random(mt_rand(0, min(3, $responsibilities->count())))
                    ->pluck('id')
                    ->all();
                $cmtyvol->responsibilities()->sync($ids);
            });
    }
}
