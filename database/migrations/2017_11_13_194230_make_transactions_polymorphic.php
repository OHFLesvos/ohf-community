<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTransactionsPolymorphic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('transactionable_id')->unsigned()->after('id');
            $table->string('transactionable_type')->after('transactionable_id');
            $table->index(['transactionable_id', 'transactionable_type']);
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('person_id')->unsigned()->after('id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade')->onUpdate('cascade');
            $table->dropIndex(['transactionable_id', 'transactionable_type']);
            $table->dropColumn('transactionable_type');
            $table->dropColumn('transactionable_id');
        });
    }
}
