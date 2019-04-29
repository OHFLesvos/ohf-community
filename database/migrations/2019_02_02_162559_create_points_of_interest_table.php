<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointsOfInterestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_of_interest', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('name_local')->nullable();
            $table->string('street')->nullable();
            $table->string('street_local')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('city_local')->nullable();
            $table->string('province')->nullable();
            $table->string('country_code')->nullable();
            $table->decimal('latitude', 8, 6)->nullable();
            $table->decimal('longitude', 8, 6)->nullable();
            $table->string('google_places_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points_of_interest');
    }
}
