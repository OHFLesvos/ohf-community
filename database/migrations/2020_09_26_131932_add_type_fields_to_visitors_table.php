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
        Schema::table('visitors', function (Blueprint $table) {
            $table->enum('type', ['visitor', 'participant', 'staff', 'external'])
                ->default('visitor')
                ->after('last_name');
            $table->string('organization')
                ->after('place_of_residence')
                ->nullable();
            $table->string('activity')
                ->after('organization')
                ->nullable();
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
            $table->dropColumn([
                'activity',
                'organization',
                'type',
            ]);
        });
    }
};
