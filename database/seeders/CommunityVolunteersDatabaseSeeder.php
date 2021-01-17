<?php

namespace Database\Seeders;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use Faker\Generator;
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

        $faker = app()->make(Generator::class);

        CommunityVolunteer::factory()
            ->count(50)
            ->create()
            ->each(function (CommunityVolunteer $cmtyvol) use ($responsibilities, $faker) {
                $ids = $responsibilities->random(mt_rand(0, min(3, $responsibilities->count())))
                    ->pluck('id')
                    ->mapWithKeys(function ($e) use($faker) {
                        $start_date = $faker->dateTimeBetween('-24 months', 'now');
                        return [$e => [
                            'start_date' => $start_date,
                            'end_date' => $faker->optional()->dateTimeBetween($start_date, 'now'),
                        ]];
                    })
                    ->all();
                $cmtyvol->responsibilities()->sync($ids);
            });
    }
}
