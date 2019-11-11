<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpersHelperResponsibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpers_helper_responsibility', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('helper_id');
            $table->unsignedBigInteger('responsibility_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('helpers_helper_responsibility');
    }
}
