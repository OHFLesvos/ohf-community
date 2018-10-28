<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowForHelpersToCouponTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_types', function (Blueprint $table) {
            $table->boolean('allow_for_helpers')->default(false)->after('max_age');
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
            $table->dropColumn('allow_for_helpers');
        });
    }
}
