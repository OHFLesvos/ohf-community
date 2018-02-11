<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_event_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('color')->nullable();
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->text('title');
            $table->text('description')->nullable();
            $table->boolean('all_day')->default(false);
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('user_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('calendar_event_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('calendar_event_types');
    }
}
