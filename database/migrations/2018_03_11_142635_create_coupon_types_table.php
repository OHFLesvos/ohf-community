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
        Schema::create('coupon_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->unsignedInteger('daily_amount');
            $table->unsignedInteger('retention_period')->nullable();
            $table->unsignedInteger('min_age')->nullable();
            $table->unsignedInteger('max_age')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_types');
    }
};
