<?php

namespace Modules\Accounting\Database\Seeders;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class MoneyTransactionSeeder extends Seeder
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
