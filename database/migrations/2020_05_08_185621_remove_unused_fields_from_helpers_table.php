<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedFieldsFromHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('helpers', function (Blueprint $table) {
            $table->dropColumn([
                'work_feedback_wishes',
                'casework_first_interview_date',
                'casework_second_interview_date',
                'casework_first_decision_date',
                'casework_appeal_date',
                'casework_second_decision_date',
                'casework_vulnerability_assessment_date',
                'casework_card_expiry_date',
                'casework_lawyer_name',
                'casework_lawyer_phone',
                'casework_lawyer_email',
                'shirt_size',
                'shoe_size',
                'urgent_needs',
                'work_needs',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('helpers', function (Blueprint $table) {
            $table->text('work_feedback_wishes')
                ->nullable();
            $table->date('casework_first_interview_date')
                ->nullable();
            $table->date('casework_second_interview_date')
                ->nullable();
            $table->date('casework_first_decision_date')
                ->nullable();
            $table->date('casework_appeal_date')
                ->nullable();
            $table->date('casework_second_decision_date')
                ->nullable();
            $table->date('casework_vulnerability_assessment_date')
                ->nullable()
                ->comment('Date when vulnerability was assessed');
            $table->date('casework_card_expiry_date')
                ->nullable();
            $table->string('casework_lawyer_name')
                ->nullable();
            $table->text('casework_lawyer_phone')
                ->nullable();
            $table->text('casework_lawyer_email')
                ->nullable();
            $table->string('shirt_size')
                ->nullable();
            $table->string('shoe_size')
                ->nullable();
            $table->string('urgent_needs')
                ->nullable();
            $table->string('work_needs')
                ->nullable();
        });
    }
}
