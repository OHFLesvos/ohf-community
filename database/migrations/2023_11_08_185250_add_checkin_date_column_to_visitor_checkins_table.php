<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('visitor_checkins', function (Blueprint $table) {
            $table->date('checkin_date')->after('visitor_id')->nullable();
            $table->unique(['visitor_id', 'checkin_date']);
        });

        // Populate the checkin_date column with data from created_at
        DB::statement('UPDATE visitor_checkins SET checkin_date = DATE(created_at)');

        Schema::table('visitor_checkins', function (Blueprint $table) {
            $table->date('checkin_date')->nullable(false)->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitor_checkins', function (Blueprint $table) {
            $table->dropUnique(['visitor_id', 'checkin_date']);
            $table->dropColumn('checkin_date');
        });
    }
};
