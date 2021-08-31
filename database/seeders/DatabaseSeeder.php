<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserRolesSeeder::class);
        $this->call(FundraisingDatabaseSeeder::class);
        $this->call(AccountingDatabaseSeeder::class);
        $this->call(WikiArticleTableSeeder::class);
        $this->call(CommunityVolunteersDatabaseSeeder::class);
        $this->call(VisitorSeeder::class);
    }
}
