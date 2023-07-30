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
        Schema::table('coupon_handouts', function (Blueprint $table) {
            $table->index(['person_id', 'date']);
            $table->index(['date', 'amount']);
        });
        // Fix issue with foreign key getting overwritten by composite key
        Schema::table('coupon_handouts', function (Blueprint $table) {
            $table->index('person_id', 'coupon_handouts_person_id_foreign');
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
            $table->dropIndex(['date', 'amount']);
            $table->dropIndex(['person_id', 'date']);
        });
    }
};
