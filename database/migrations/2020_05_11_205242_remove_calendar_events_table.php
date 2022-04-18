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
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('calendar_resources');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('calendar_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('color')->nullable();
            $table->string('group')->nullable();
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->text('title');
            $table->text('description')->nullable();
            $table->boolean('all_day')->default(false);
            $table->unsignedInteger('resource_id');
            $table->unsignedInteger('user_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('resource_id')->references('id')->on('calendar_resources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
