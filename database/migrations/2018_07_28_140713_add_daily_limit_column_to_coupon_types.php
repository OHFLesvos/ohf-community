<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyLimitColumnToCouponTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_types', function (Blueprint $table) {
            $table->unsignedInteger('daily_spending_limit')->nullable()->after('max_age');
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
            $table->dropColumn('daily_spending_limit');
        });
    }
}
