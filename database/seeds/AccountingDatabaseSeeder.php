<?php

use App\Models\Accounting\MoneyTransaction;

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
        factory(MoneyTransaction::class, 250)->create();
    }
}
