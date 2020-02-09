<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonBoutiqueCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('persons', function (Blueprint $table) {
			$table->timestamp('boutique_coupon')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('persons', function (Blueprint $table) {
			$table->dropColumn('boutique_coupon');
		});
    }
}
