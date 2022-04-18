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
        Schema::table('donors', function (Blueprint $table) {
            $table->string('salutation')->nullable()->after('id');

            // Fix column type
            $table->string('street')->change();
            $table->string('company')->change();
            $table->string('last_name')->change();
            $table->string('first_name')->change();
            $table->string('country_code')->change();
            $table->string('language')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('salutation');
        });
    }
};
