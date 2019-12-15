<?php

use Modules\Bank\Entities\CouponType;

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

        if (Setting::has('shop.coupon_valid_days')) {
            $coupon_valid_days = Setting::get('shop.coupon_valid_days');
            if ($coupon_valid_days != null) {
                CouponType::where('qr_code_enabled', true)->update(['code_expiry_days' => $coupon_valid_days]);
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
}
