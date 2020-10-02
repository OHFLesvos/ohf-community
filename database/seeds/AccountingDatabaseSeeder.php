<?php

use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Supplier;
use App\Models\Accounting\Wallet;
use Illuminate\Database\Seeder;

class AccountingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Supplier::class, 100)->create();

        factory(Wallet::class, 3)->create()
           ->each(function ($wallet) {
                $wallet->transactions()->createMany(
                    factory(MoneyTransaction::class, mt_rand(50, 250))->make()->toArray()
                );
            });
    }
}
