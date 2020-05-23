<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiptNoCorrectionAndMoreCategorizationColumnsToMoneyTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('receipt_no_correction')->after('receipt_no')->nullable();
            $table->string('secondary_category')->after('category')->nullable();
            $table->string('location')->after('project')->nullable();
            $table->string('cost_center')->after('location')->nullable();
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
            $table->dropColumn('receipt_no_correction');
            $table->dropColumn('secondary_category');
            $table->dropColumn('location');
            $table->dropColumn('cost_center');
        });
    }
}
