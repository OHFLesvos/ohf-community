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
        $projects = [
            'Kitchen',
            'Transportation',
            'School',
            'Garden',
            'Sports'
        ];

        MoneyTransaction::create([
            'date' => Carbon::today()->subDays(2),
            'type' => 'income',
            'amount' => 150,
            'beneficiary' => 'Anna Chang',
            'project' => 'Volunteer Payment',
            'description' => 'Peter Muster',
        ]);
        for ($i = 0; $i < 100; $i++) {
            MoneyTransaction::create([
                'date' => Carbon::today()->subDays(rand(0, 60)),
                'type' => 'spending',
                'amount' => rand(10,200),
                'beneficiary' => 'Anna Chang',
                'project' => $projects[array_rand($projects)],
                'description' => 'Goods',
            ]);
        }
    }
}
