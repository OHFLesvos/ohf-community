<?php

use App\Models\CommunityVolunteers\CommunityVolunteer;
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
        CommunityVolunteer::query()
            ->whereNotNull('work_starting_date')
            ->orWhereNotNull('work_leaving_date')
            ->get()
            ->each(function ($cmtyvol) {
                $cmtyvol->responsibilities->each(function ($responsibility) use ($cmtyvol) {
                    if ($responsibility->pivot->start_date == null) {
                        $responsibility->pivot->start_date = $cmtyvol->work_starting_date;
                    }
                    if ($responsibility->pivot->end_date == null) {
                        $responsibility->pivot->end_date = $cmtyvol->work_leaving_date;
                    }
                    $responsibility->pivot->save();
                });
            });

        Schema::table('community_volunteers', function (Blueprint $table) {
            $table->dropColumn([
                'work_starting_date',
                'work_leaving_date',
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
            $table->date('work_starting_date')->nullable()->after('pickup_location');
            $table->date('work_leaving_date')->nullable()->after('work_starting_date');
        });
    }
};
