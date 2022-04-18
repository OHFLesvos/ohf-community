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
        Schema::table('coupon_types', function (Blueprint $table) {
            $table->unsignedInteger('newly_registered_block_days')->nullable()->after('daily_spending_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_types', function (Blueprint $table) {
            $table->dropColumn('newly_registered_block_days');
        });
    }
};
