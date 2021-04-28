<?php

use App\Models\Accounting\Category;
use App\Models\Accounting\MoneyTransaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreign('parent_id')->references('id')->on('accounting_categories')->cascadeOnDelete();
            $table->timestamps();
        });

        if (Setting::has('accounting.transactions.categories')) {
            collect(Setting::get('accounting.transactions.categories'))->each(function ($name) {
                Category::firstOrCreate(
                    ['name' => $name],
                    ['name' => $name]
                );
            });
            Setting::forget('accounting.transactions.categories');
        }

        $hasTransactions = MoneyTransaction::exists();
        if ($hasTransactions) {
            Schema::table('money_transactions', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->after('attendee');
                $table->foreign('category_id')->references('id')->on('accounting_categories')->restrictOnDelete();
            });

            MoneyTransaction::all()->each(function ($transaction) {
                $category = Category::firstOrCreate(
                    ['name' => $transaction->category],
                    ['name' => $transaction->category]
                );
                $transaction->category()->associate($category);
                $transaction->save();
            });

            Schema::table('money_transactions', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable(false)->change();
            });
        } else {
            Schema::table('money_transactions', function (Blueprint $table) {
                $table->foreignId('category_id')->after('wallet_id');
                $table->foreign('category_id')->references('id')->on('accounting_categories')->restrictOnDelete();
            });
        }

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        DB::statement($this->dropView());
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('category_temp')->after('attendee')->nullable();
        });

        MoneyTransaction::all()->each(function ($transaction) {
            $transaction->category_temp = $transaction->category->name;
            $transaction->save();
        });

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->renameColumn('category_temp', 'category');
        });

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Setting::set('accounting.transactions.categories', Category::pluck('name'));

        Schema::dropIfExists('accounting_categories');
    }

    private function dropView(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `accounting_signed_transactions`;
SQL;
    }

    private function createView(): string
    {
        return <<<SQL
CREATE VIEW `accounting_signed_transactions` AS
SELECT wallet_id, date, -amount as amount, fees, receipt_no, category_id, project, description, remarks from money_transactions where type = 'spending'
union all
SELECT wallet_id, date, amount, fees, receipt_no, category_id, project, description, remarks from money_transactions where type = 'income'
SQL;
    }
}
