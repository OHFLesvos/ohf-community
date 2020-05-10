<?php

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\People\Person;
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
                ->after('id');
            $table->string('family_name')
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

        CommunityVolunteer::all()
            ->each(function ($cmtyvol) {
                $person = Person::find($cmtyvol->person_id);
                $cmtyvol->first_name = $person->name;
                $cmtyvol->family_name = $person->family_name;
                $cmtyvol->nickname = $person->nickname;
                $cmtyvol->date_of_birth = $person->date_of_birth;
                $cmtyvol->gender = $person->gender;
                $cmtyvol->nationality = $person->nationality;
                $cmtyvol->languages = $person->languages;
                $cmtyvol->portrait_picture = $person->portrait_picture;
                $cmtyvol->police_no = $person->police_no;
                $cmtyvol->notes = filled($cmtyvol->notes)
                    ? $cmtyvol->notes . "\n" . $person->remarks
                    : $cmtyvol->notes;
                $cmtyvol->save();
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
