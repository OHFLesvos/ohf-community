<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameHelpersResponsibilitiesToCommunityVolunteerResponsibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('helpers_responsibilities', 'community_volunteer_responsibilities');
        Schema::rename('helpers_helper_responsibility', 'community_volunteer_responsibility');
        Schema::table('community_volunteer_responsibility', function (Blueprint $table) {
            $table->renameColumn('helper_id', 'community_volunteer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_volunteer_responsibility', function (Blueprint $table) {
            $table->renameColumn('community_volunteer_id', 'helper_id');
        });
        Schema::rename('community_volunteer_responsibility', 'helpers_helper_responsibility');
        Schema::rename('community_volunteer_responsibilities', 'helpers_responsibilities');
    }
}
