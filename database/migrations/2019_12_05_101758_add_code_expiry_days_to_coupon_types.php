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
            $table->unsignedInteger('code_expiry_days')->nullable()->after('qr_code_enabled');
        });

        if (Setting::has('shop.coupon_valid_days')) {
            $coupon_valid_days = Setting::get('shop.coupon_valid_days');
            if ($coupon_valid_days != null) {
                DB::table('coupon_types')->where('qr_code_enabled', true)->update(['code_expiry_days' => $coupon_valid_days]);
            }
            Setting::forget('shop.coupon_valid_days');
        }
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
};
