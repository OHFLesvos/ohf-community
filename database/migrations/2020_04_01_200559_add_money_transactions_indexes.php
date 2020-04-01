<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoneyTransactionsIndexes extends Migration
{
    public function __construct()
    {
        // workaround for laravels limitation to change tables with an enum
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('beneficiary')->change();
            $table->string('project')->change();
            $table->string('description')->change();

            $table->index(['beneficiary']);
            $table->index(['category']);
            $table->index(['project']);
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
            $table->dropIndex(['beneficiary']);
            $table->dropIndex(['category']);
            $table->dropIndex(['project']);

            $table->text('beneficiary')->change();
            $table->text('project')->change();
            $table->text('description')->change();
        });
    }
}
