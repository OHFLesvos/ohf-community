<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMoneyTransactionsToAccountingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('money_transactions', 'accounting_transactions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['project']);
        });

        Schema::rename('accounting_transactions', 'money_transactions');

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->index(['category']);
            $table->index(['project']);
        });
    }
}
