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
        DB::table('visitors')->update(['first_name' => DB::raw("TRIM(CONCAT(first_name, ' ', last_name))")]);
        DB::table('visitors')->whereIn('type', ['staff', 'external'])->delete();

        Schema::create('visitor_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')
                ->constrained('visitors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('purpose_of_visit')->nullable();
            $table->timestamps();
        });

        DB::table('visitors')->lazyById()->each(function ($visitor) {
            DB::table('visitor_checkins')->insert([
                'visitor_id' => $visitor->id,
                'purpose_of_visit' => $visitor->activity,
                'created_at' => $visitor->entered_at,
                'updated_at' => $visitor->left_at !== null ? $visitor->left_at : $visitor->entered_at,
            ]);
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->renameColumn('first_name', 'name');
            $table->dropColumn('last_name');
            $table->dropColumn('type');
            $table->renameColumn('place_of_residence', 'living_situation');
            $table->dropColumn('organization');
            $table->dropColumn('activity');
            $table->dropColumn('entered_at');
            $table->dropColumn('left_at');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('id_number');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('nationality')->nullable()->after('date_of_birth');
            $table->boolean('anonymized')->default(false)->after('place_of_residence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
            $table->string('last_name')->after('name');
            $table->enum('type', ['visitor', 'participant', 'staff', 'external'])
                ->default('visitor')
                ->after('last_name');
            $table->renameColumn('living_situation', 'place_of_residence');
            $table->string('organization')
                ->after('living_situation')
                ->nullable();
            $table->string('activity')
                ->after('organization')
                ->nullable();
            $table->dateTime('entered_at');
            $table->dateTime('left_at')->nullable();
            $table->dropColumn('gender');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('nationality');
            $table->dropColumn('anonymized');
        });

        DB::table('visitor_checkins')->lazyById()->each(function ($checkin) {
            DB::table('visitors')
                ->where('id', $checkin->visitor_id)
                ->update([
                    'activity' => $checkin->purpose_of_visit,
                    'entered_at' => $checkin->created_at,
                ]);
        });

        Schema::dropIfExists('visitor_checkins');
    }
};
