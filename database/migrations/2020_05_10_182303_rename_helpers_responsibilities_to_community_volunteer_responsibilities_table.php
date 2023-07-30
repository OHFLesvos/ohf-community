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
        Schema::rename('helpers_responsibilities', 'community_volunteer_responsibilities');
        Schema::table('helpers_helper_responsibility', function (Blueprint $table) {
            $table->dropForeign(['helper_id']);
        });
        Schema::rename('helpers_helper_responsibility', 'community_volunteer_responsibility');
        Schema::table('community_volunteer_responsibility', function (Blueprint $table) {
            $table->renameColumn('helper_id', 'community_volunteer_id');
        });
        Schema::table('community_volunteer_responsibility', function (Blueprint $table) {
            $table->foreign('community_volunteer_id', 'community_volunteer_responsibility_cv_id_foreign')
                ->references('id')
                ->on('community_volunteers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
            $table->dropForeign(['cv_id']);
        });
        Schema::table('community_volunteer_responsibility', function (Blueprint $table) {
            $table->renameColumn('community_volunteer_id', 'helper_id');
        });
        Schema::rename('community_volunteer_responsibility', 'helpers_helper_responsibility');
        Schema::table('helpers_helper_responsibility', function (Blueprint $table) {
            $table->foreign('helper_id')
                ->references('id')
                ->on('community_volunteers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::rename('community_volunteer_responsibilities', 'helpers_responsibilities');
    }
};
