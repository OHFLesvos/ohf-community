<?php

use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameHelpersToCommunityVolunteersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('helpers', 'community_volunteers');

        Schema::table('community_volunteers', function (Blueprint $table) {
            $table->string('first_name')
                ->nullable()
                ->after('id');
            $table->string('family_name')
                ->nullable()
                ->after('first_name');
            $table->string('nickname')
                ->nullable()
                ->after('family_name');
            $table->date('date_of_birth')
                ->nullable()
                ->after('nickname');
            $table->enum('gender', ['m', 'f'])
                ->nullable()
                ->after('date_of_birth');
            $table->string('nationality')
                ->nullable()
                ->after('gender');
            $table->string('languages')
                ->nullable()
                ->after('nationality');
            $table->string('portrait_picture')
                ->nullable()
                ->after('languages');
            $table->unsignedInteger('police_no')
                ->nullable()
                ->after('portrait_picture');
        });

        CommunityVolunteer::whereNotNull('deleted_at')->delete();

        Schema::table('community_volunteers', function (Blueprint $table) {
            $table->string('first_name')
                ->nullable(false)
                ->change();
            $table->string('family_name')
                ->nullable(false)
                ->change();
        });

        Schema::table('community_volunteers', function (Blueprint $table) {
            $table->dropForeign('helpers_person_id_foreign');
            $table->dropColumn([
                'person_id',
                'deleted_at',
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
        Schema::table('community_volunteers', function (Blueprint $table) {
            $table->unsignedInteger('person_id')
                ->after('id')
                ->nullable();
            $table->foreign('person_id')
                ->references('id')
                ->on('persons')
                ->onDelete('cascade');
            $table->softDeletes();
        });

        Schema::table('community_volunteers', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'family_name',
                'nickname',
                'date_of_birth',
                'gender',
                'nationality',
                'languages',
                'portrait_picture',
                'police_no',
            ]);
        });

        Schema::rename('community_volunteers', 'helpers');
    }
}
