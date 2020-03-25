<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurposeReferennceToDonation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->renameColumn('origin', 'channel');
        });
        Schema::table('donations', function (Blueprint $table) {
            $table->string('purpose')->after('channel')->nullable();
            $table->string('reference')->after('purpose')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('reference');
            $table->dropColumn('purpose');
        });
        Schema::table('donations', function (Blueprint $table) {
            $table->renameColumn('channel', 'origin');
        });
    }
}
