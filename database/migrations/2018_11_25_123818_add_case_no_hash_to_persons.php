<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Person;

class AddCaseNoHashToPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->string('case_no_hash')->nullable()->after('case_no');
            $table->index('case_no_hash', 'persons_case_no_hash_index');
            $table->index('police_no', 'persons_police_no_index');
            $table->index('registration_no', 'persons_registration_no_index');
            $table->index('section_card_no', 'persons_section_card_no_index');
        });
        Schema::table('helpers', function (Blueprint $table) {
            $table->string('casework_case_number')->nullable()->after('endorses_casework');
        });
        Person::all()->each(function($p) {
            if ($p->case_no != null) {
                $p->case_no_hash = hash('sha256', $p->case_no);
                $p->save();
                if ($person->helper != null) {
                    $person->helper->casework_case_number = $p->case_no;
                    $person->helper->save();
                }
            }
        });
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('case_no');
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
            $table->dropIndex('persons_police_no_index');
            $table->dropIndex('persons_registration_no_index');
            $table->dropIndex('persons_section_card_no_index');
            $table->dropIndex('persons_case_no_hash_index');
            $table->bigInteger('case_no')->nullable()->after('police_no');
            $table->dropColumn('case_no_hash');
        });
    }
}
