<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(ProjectsSeeder::class);
        $this->call(CouponTypesSeeder::class);
        $this->call(InventoryStorageSeeder::class);

        $this->call(PersonsTableSeeder::class);
        $this->call(InventoryItemTransactionSeeder::class);
        $this->call(LibraryBookSeeder::class);
    }
}
