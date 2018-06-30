<?php

use Illuminate\Database\Seeder;
use App\InventoryStorage;
use App\InventoryItemTransaction;

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
                'source' => 'Cash & Carry',
            ],
            [
                'item' => "Rice (10 KG)",
                'quantity' => 3,
                'source' => 'Cash & Carry',
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
                'source' => 'Garden',
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
