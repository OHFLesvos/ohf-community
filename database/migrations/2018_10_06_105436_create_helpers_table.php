<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('person_id');
            $table->text('projects')->nullable();
            $table->string('local_phone')->nullable();
            $table->string('other_phone')->nullable();
            $table->string('whatsapp')->nullable();
            // police no. field already in "persons" table
            // DoB field already in "persons" table
            $table->date('application_date')->nullable();
            $table->date('rejection_date')->nullable();
            $table->date('starting_date')->nullable();
            $table->boolean('trial_period');
            // case number field already in "persons" table
            $table->boolean('casework')->default(true);
            $table->date('casework_interview_date')->nullable();
            $table->date('casework_first_decision_date')->nullable();
            $table->date('casework_appeal_date')->nullable();
            $table->date('casework_second_decision_date')->nullable();
            $table->date('casework_vulnerable_date')->nullable();
            $table->date('casework_card_expiry_date')->nullable();
            $table->string('casework_lawyer_name')->nullable();
            $table->text('casework_lawyer_contact')->nullable();
            $table->text('background')->nullable()->comment("Profession before Lesbos, secret talents, ambitions");
            $table->text('improvements')->nullable()->comment("Improvements in their work");
            // languages field already in "persons" table
            $table->text('residence')->nullable();
            // notes (remarks) field already in "persons" table
            $table->date('leaving_date')->nullable();
            $table->text('destination')->nullable()->comment("Last known destination or residency");
            $table->timestamps();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('helpers');
    }
}
