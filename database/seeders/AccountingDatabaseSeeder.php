<?php

namespace Database\Seeders;

use App\Models\Accounting\Budget;
use App\Models\Accounting\Category;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Project;
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

        $categories = Category::factory()
            ->count(30)
            ->create();

        $projects = Project::factory()
            ->count(60)
            ->create();

        Wallet::factory()
            ->count(3)
            ->create()
            ->each(function (Wallet $wallet) use($categories, $projects) {
                $wallet->transactions()->createMany(
                    Transaction::factory()
                        ->count(mt_rand(50, 250))
                        ->make()
                        ->map(function ($e) use($categories, $projects)  {
                            $e['category_id'] = $categories->random()->id;
                            if (mt_rand(1,100) > 60) {
                                $e['project_id'] = $projects->random()->id;
                            }
                            return $e;
                        })
                        ->toArray()
                );
            });

        Budget::factory()
            ->count(20)
            ->create()
            ->each(function(Budget $budget) {
                Transaction::inRandomOrder()
                    ->limit(mt_rand(5, 10))
                    ->get()
                    ->each(function(Transaction $transaction) use ($budget) {
                        $transaction->budget()->associate($budget);
                        $transaction->save();
                    });
            });

    }
}
