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
        Schema::table('community_volunteers', function (Blueprint $table) {
            $table->string('police_no')->change();
        });

        DB::table('community_volunteers')
            ->whereNotNull('police_no')
            ->update(['police_no' => DB::raw("TRIM('05/' || police_no)")]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_volunteers', function (Blueprint $table) {
            $table->unsignedInteger('police_no')->change();
        });
    }
};
