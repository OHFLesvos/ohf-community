<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatePersonIdIndexToCouponHandouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_handouts', function (Blueprint $table) {
            $table->index(['person_id', 'date'], 'person_id_date_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_handouts', function (Blueprint $table) {
            $table->dropIndex('person_id_date_index');
        });
    }
}
