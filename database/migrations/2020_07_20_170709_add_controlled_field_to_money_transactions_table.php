<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddControlledFieldToMoneyTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->timestamp('controlled_at')
                ->nullable()
                ->after('external_id');
            $table->unsignedInteger('controlled_by')
                ->nullable()
                ->after('controlled_at');
            $table->foreign('controlled_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
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
            $table->dropForeign(['controlled_by']);
            $table->dropColumn('controlled_by');
            $table->dropColumn('controlled_at');
        });
    }
}
