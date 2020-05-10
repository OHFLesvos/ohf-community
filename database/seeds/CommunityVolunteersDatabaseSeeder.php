<?php

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

        $responsibilities = factory(Responsibility::class, 15)->create();

        factory(CommunityVolunteer::class, 50)->create()->each(function ($cmtyvol) use ($responsibilities) {
            $ids = $responsibilities->random(mt_rand(0, min(3, $responsibilities->count())))
                ->pluck('id')
                ->all();
            $cmtyvol->responsibilities()->sync($ids);
        });
    }
}
