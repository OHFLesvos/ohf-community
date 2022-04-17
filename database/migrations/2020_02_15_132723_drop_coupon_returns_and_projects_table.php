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
        Schema::dropIfExists('coupon_returns');
        Schema::dropIfExists('projects');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->boolean('enable_in_bank')->default(false);
            $table->timestamps();
        });

        Schema::create('coupon_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('amount');
            $table->integer('coupon_type_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->timestamps();
            $table->foreign('coupon_type_id')->references('id')->on('coupon_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['date', 'coupon_type_id', 'project_id']);
        });
    }
};
