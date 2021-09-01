<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_budgets', function (Blueprint $table) {
            $table->decimal('agreed_amount', 8, 2);
            $table->renameColumn('amount', 'initial_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_budgets', function (Blueprint $table) {
            $table->renameColumn('initial_amount', 'amount');
            $table->dropColumn('agreed_amount');
        });
    }
};
