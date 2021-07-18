<?php

use App\Models\Accounting\Category;
use App\Models\Accounting\Transaction;
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
            $table->boolean('enabled')->default(true);
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

        $hasTransactions = Transaction::exists();
        if ($hasTransactions) {
            Schema::table('accounting_transactions', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->after('attendee');
            });

            Transaction::all()->each(function ($transaction) {
                $category = Category::firstOrCreate(
                    ['name' => $transaction->category],
                    ['name' => $transaction->category]
                );
                $transaction->category()->associate($category);
                $transaction->save();
            });

            Schema::table('accounting_transactions', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable(false)->change();
                $table->foreign('category_id')->references('id')->on('accounting_categories')->restrictOnDelete();
            });
        } else {
            Schema::table('accounting_transactions', function (Blueprint $table) {
                $table->foreignId('category_id')->after('wallet_id');
                $table->foreign('category_id')->references('id')->on('accounting_categories')->restrictOnDelete();
            });
        }

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->string('category_temp')->after('attendee')->nullable();
        });

        Transaction::all()->each(function ($transaction) {
            $transaction->category_temp = $transaction->category->name;
            $transaction->save();
        });

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->renameColumn('category_temp', 'category');
        });

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->index(['category']);
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Setting::set('accounting.transactions.categories', Category::pluck('name'));

        Schema::dropIfExists('accounting_categories');
    }
}
