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
        Schema::table('community_volunteer_responsibility', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('responsibility_id');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_volunteer_responsibility', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
