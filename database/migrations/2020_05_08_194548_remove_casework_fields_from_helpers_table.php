<?php

use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCaseworkFieldsFromHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CommunityVolunteer::all()->each(function (CommunityVolunteer $helper) {
            $notes = '';

            if (filled($helper->work_background)) {
                $notes .= "Background: $helper->work_background\n";
            }
            if (filled($helper->has_tax_number)) {
                $tax_number_states = collect([
                    'yes' => 'Yes',
                    'no' => 'No',
                    'applied' => 'Applied',
                ]);
                $notes .= "Tax number: " .  $tax_number_states->get($helper->has_tax_number, $helper->has_tax_number) . "\n";
            }
            if (filled($helper->endorses_casework)) {
                $value = $helper->has_tax_number ? 'Yes' : 'No';
                $notes .= "Case work: $value\n";
            }
            if (filled($helper->casework_case_number)) {
                $notes .= "Case number: $helper->casework_case_number\n";
            }
            if (filled($helper->casework_asylum_request_status)) {
                $asylum_request_states = collect([
                    'awaiting_interview' => 'Awaiting interview',
                    'waiting_for_decision' => 'Waiting for decision',
                    'first_rejection' => 'First rejection',
                    'second_rejection' => 'Second rejection',
                    'subsidiary_protection' => 'Subsidiary protection',
                    'refugee_status' => 'Refugee status',
                ]);
                $notes .= "Asylum Request Status: " . $asylum_request_states->get($helper->casework_asylum_request_status, $helper->casework_asylum_request_status) . "\n";
            }
            if (filled($helper->casework_has_geo_restriction)) {
                $value = $helper->casework_has_geo_restriction ? 'Yes' : 'No';
                $notes .= "Has geographical Restriction? $value\n";
            }
            if (filled($helper->casework_has_id_card)) {
                $value = $helper->casework_has_id_card ? 'Yes' : 'No';
                $notes .= "Has ID card? $value\n";
            }
            if (filled($helper->casework_has_passport)) {
                $value = $helper->casework_has_passport ? 'Yes' : 'No';
                $notes .= "Has passport? $value\n";
            }
            if (filled($helper->casework_vulnerability)) {
                $value = $helper->casework_vulnerability ? 'Yes' : 'No';
                $notes .= "Vulnerability: $value\n";
            }
            if (filled(trim($notes))) {
                $helper->notes = trim($helper->notes . "\n\n" . $notes);
                $helper->save();
            }
        });

        Schema::table('helpers', function (Blueprint $table) {
            $table->dropColumn([
                'work_background',
                'has_tax_number',
                'endorses_casework',
                'casework_case_number',
                'casework_asylum_request_status',
                'casework_has_geo_restriction',
                'casework_has_id_card',
                'casework_has_passport',
                'casework_vulnerability',
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
            $table->text('work_background')->nullable()->comment("Profession before, secret talents, ambitions");
            $table->enum('has_tax_number', ['yes', 'no', 'applied'])->nullable()->default(null)->after('work_leaving_date');
            $table->boolean('endorses_casework')->nullable();
            $table->string('casework_case_number')->nullable()->after('endorses_casework');
            $table->enum('casework_asylum_request_status', ['awaiting_interview', 'waiting_for_decision', 'first_rejection', 'second_rejection', 'subsidiary_protection', 'refugee_status'])->nullable();
            $table->boolean('casework_has_geo_restriction')->nullable();
            $table->boolean('casework_has_id_card')->nullable();
            $table->boolean('casework_has_passport')->nullable();
            $table->string('casework_vulnerability')->nullable();
        });
    }
}
