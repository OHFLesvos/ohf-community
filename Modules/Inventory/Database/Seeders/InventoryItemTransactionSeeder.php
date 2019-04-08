<?php

namespace Modules\Inventory\Database\Seeders;

use Modules\Inventory\Entities\InventoryStorage;
use Modules\Inventory\Entities\InventoryItemTransaction;

use Illuminate\Database\Seeder;

class InventoryItemTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $foodContainer = InventoryStorage::find(1);

        foreach([
            [
                'item' => "Rice (10 KG)",
                'quantity' => 5,
                'origin' => 'Cash & Carry',
            ],
            [
                'item' => "Rice (10 KG)",
                'quantity' => 3,
                'origin' => 'Cash & Carry',
            ],
            [
                'item' => "Rice (10 KG)",
                'quantity' => -4,
                'destination' => 'Kitchen',
            ],
            [
                'item' => "Potatoes (20 KG Bag)",
                'quantity' => 1,
            ],
            [
                'item' => "Tomatoes (Box)",
                'quantity' => 2,
                'origin' => 'Garden',
            ],
        ] as $data) {
            $transaction = new InventoryItemTransaction();
            foreach ($data as $k => $v) {
                $transaction->$k = $v;
            }
            $foodContainer->transactions()->save($transaction);
        }
    }
}
