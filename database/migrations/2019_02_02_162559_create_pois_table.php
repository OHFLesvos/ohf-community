<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoisTable extends Migration
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
            $table->string('address');
            $table->string('address_local')->nullable();
            $table->decimal('lat')->nullable();
            $table->decimal('long')->nullable();
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
