<?php

namespace Modules\Inventory\Database\Seeders;

use Modules\Inventory\Entities\InventoryStorage;

use Illuminate\Database\Seeder;

class InventoryStorageSeeder extends Seeder
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
        ])->each(function ($storage) { 
            InventoryStorage::create($storage); 
        });
    }
}
