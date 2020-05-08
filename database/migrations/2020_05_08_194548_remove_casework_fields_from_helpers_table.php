<?php

use App\Models\Helpers\Helper;
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
        Helper::all()->each(function (Helper $helper) {
            $notes = '';

            if (filled($helper->work_background)) {
                $notes .= "Background: $helper->work_background\n";
            }
            if (filled($helper->has_tax_number)) {
                $value = $helper->has_tax_number ? 'Yes' : 'No';
                $notes .= "Tax number: $value\n";
            }
            if (filled($helper->endorses_casework)) {
                if ($helper->endorses_casework === false) {
                    $notes .= "Case work: No\n";
                } else {
                    $notes .= "Case work: Yes\n";
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
                }
            }

            if (filled(trim($notes))) {
                $helper->notes = trim($helper->notes . "\n\n" . $notes);
                $helper->save();
            }
        });

        Schema::table('helpers', function (Blueprint $table) {
            $table->dropColumn('work_background');
            $table->dropColumn('has_tax_number');
            $table->dropColumn('endorses_casework');
            $table->dropColumn('casework_case_number');
            $table->dropColumn('casework_asylum_request_status');
            $table->dropColumn('casework_has_geo_restriction');
            $table->dropColumn('casework_has_id_card');
            $table->dropColumn('casework_has_passport');
            $table->dropColumn('casework_vulnerability');
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
