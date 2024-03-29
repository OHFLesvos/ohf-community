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
        Schema::create('helpers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('person_id');
            $table->text('responsibilities')->nullable()->comment('Areas of responsibility the helper is involved in');
            $table->string('local_phone')->nullable();
            $table->string('other_phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('skype')->nullable();
            $table->text('residence')->nullable();
            // police no. field already in "persons" table
            // DoB field already in "persons" table
            $table->date('work_application_date')->nullable();
            $table->date('work_rejection_date')->nullable();
            $table->date('work_starting_date')->nullable();
            $table->boolean('work_trial_period')->nullable();
            $table->text('work_background')->nullable()->comment('Profession before, secret talents, ambitions');
            $table->text('work_feedback_wishes')->nullable();
            $table->date('work_leaving_date')->nullable();
            // case number field already in "persons" table
            $table->boolean('endorses_casework')->nullable();
            $table->enum('casework_asylum_request_status', ['awaiting_interview', 'waiting_for_decision', 'first_rejection', 'second_rejection', 'subsidiary_protection', 'refugee_status'])->nullable();
            $table->boolean('casework_has_geo_restriction')->nullable();
            $table->boolean('casework_has_id_card')->nullable();
            $table->boolean('casework_has_passport')->nullable();
            $table->date('casework_first_interview_date')->nullable();
            $table->date('casework_second_interview_date')->nullable();
            $table->date('casework_first_decision_date')->nullable();
            $table->date('casework_appeal_date')->nullable();
            $table->date('casework_second_decision_date')->nullable();
            $table->date('casework_vulnerability_assessment_date')->nullable()->comment('Date when vulnerability was assessed');
            $table->string('casework_vulnerability')->nullable();
            $table->date('casework_card_expiry_date')->nullable();
            $table->string('casework_lawyer_name')->nullable();
            $table->text('casework_lawyer_phone')->nullable();
            $table->text('casework_lawyer_email')->nullable();
            $table->string('shirt_size')->nullable();
            $table->string('shoe_size')->nullable();
            $table->string('urgent_needs')->nullable();
            $table->string('work_needs')->nullable();
            $table->text('notes')->nullable();
            // languages field already in "persons" table
            // notes (remarks) field already in "persons" table
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('persons', function (Blueprint $table) {
            $table->string('nickname')->nullable()->after('family_name');
            $table->string('portrait_picture')->nullable()->after('languages');
            $table->dropColumn(['worker', 'skills']);
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
            $table->dropColumn('portrait_picture');
        });

        Schema::dropIfExists('helpers');
    }
};
