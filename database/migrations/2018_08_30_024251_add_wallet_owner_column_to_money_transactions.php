<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWalletOwnerColumnToMoneyTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('category')->after('beneficiary')->nullable();
            $table->string('wallet_owner')->after('description')->nullable();
            $table->string('remarks')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->dropColumn('remarks');
            $table->dropColumn('wallet_owner');
            $table->dropColumn('category');
        });
    }
}
