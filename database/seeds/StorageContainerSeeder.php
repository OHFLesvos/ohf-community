<?php

use App\StorageContainer;
use Illuminate\Database\Seeder;

class StorageContainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => 'Food Container',
            ],
            [
                'name' => 'NFI Container',
                'description' => 'Storage for Non-Food Items',
            ],
            [
                'name' => 'Tool Storage',
            ],
        ])->each(function ($container) { 
            StorageContainer::create($container); 
        });
    }
}
