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
        Schema::table('helpers', function (Blueprint $table) {
            $table->dropColumn('work_trial_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('helpers', function (Blueprint $table) {
            $table->boolean('work_trial_period')
                ->nullable()
                ->after('work_starting_date');
        });
    }
};
