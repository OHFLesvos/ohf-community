<?php

namespace Database\Seeders;

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
        Supplier::factory()
            ->count(150)
            ->create();

        Wallet::factory()
            ->count(3)
            ->create()
            ->each(function (Wallet $wallet) {
                $wallet->transactions()->createMany(
                    MoneyTransaction::factory()
                        ->count(mt_rand(50, 250))
                        ->make()
                        ->toArray()
                );
            });
    }
}
