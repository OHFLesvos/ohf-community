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
            $table->boolean('qr_code_enabled')->after('daily_spending_limit')->default(false);
        });
        Schema::table('coupon_handouts', function (Blueprint $table) {
            $table->date('code_redeemed')->nullable()->after('person_id');
            $table->string('code')->nullable()->after('person_id');
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
            $table->dropColumn('qr_code_enabled');
        });
        Schema::table('coupon_handouts', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'code_redeemed',
            ]);
        });
    }
};
