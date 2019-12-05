<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeExpiryDaysToCouponTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_types', function (Blueprint $table) {
            $table->unsignedInteger('code_expiry_days')->nullable()->after('qr_code_enabled');
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
            $table->dropColumn('code_expiry_days');
        });
    }
}
