<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class FundraisingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = Tag::factory()
            ->count(mt_rand(5, 15))
            ->make()
            ->pluck('name');

        Donor::factory()
            ->count(1000)
            ->create()
            ->each(function (Donor $donor) use ($tags) {
                // Donations
                $donor->addDonations(Donation::factory()
                    ->count(mt_rand(1, 10))
                    ->make());

                // Comments
                if (mt_rand(0, 100) < 15) {
                    $donor->addComments(Comment::factory()
                        ->count(mt_rand(1, 3))
                        ->make());
                }

                // Tags
                $donor->setTags($tags->random(mt_rand(0, 3)));
            });
    }
}
