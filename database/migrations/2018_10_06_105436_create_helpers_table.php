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
            $table->text('responsibilities')->nullable()->comment('Areas of responsibility the helper is involved in');
            $table->string('local_phone')->nullable();
            $table->string('other_phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('skype')->nullable();
            // police no. field already in "persons" table
            // DoB field already in "persons" table
            $table->date('application_date')->nullable();
            $table->date('rejection_date')->nullable();
            $table->date('starting_date')->nullable();
            $table->boolean('trial_period')->nullable();
            // case number field already in "persons" table
            $table->boolean('casework')->nullable();
            $table->enum('casework_status', ['applicant', 'first_rejection', 'second_rejection', 'asylum_granted'])->nullable();
            $table->boolean('casework_geo_restriction')->nullable();
            $table->date('casework_interview_date')->nullable();
            $table->date('casework_first_decision_date')->nullable();
            $table->date('casework_appeal_date')->nullable();
            $table->date('casework_second_decision_date')->nullable();
            $table->date('casework_vulnerable_date')->nullable()->comment('Date when vulnerability was assessed');
            $table->boolean('casework_vulnerable')->nullable()->comment('Person has vulnerability status');
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
            $table->softDeletes();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('persons', function (Blueprint $table) {
            $table->string('nickname')->nullable()->after('family_name');
            $table->dropColumn('worker');
            $table->dropColumn('skills');
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
            $table->string('skills')->nullable();
            $table->boolean('worker')->default(false);
            $table->dropColumn('nickname');
        });

        Schema::dropIfExists('helpers');
    }
}
