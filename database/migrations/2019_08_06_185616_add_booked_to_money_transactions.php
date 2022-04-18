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
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->unsignedInteger('external_id')->after('wallet_owner')->nullable();
            $table->boolean('booked')->default(false)->after('wallet_owner');
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
            $table->dropColumn('booked');
            $table->dropColumn('external_id');
        });
    }
};
