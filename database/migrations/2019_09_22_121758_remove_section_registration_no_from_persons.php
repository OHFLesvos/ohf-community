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
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn(['section_card_no', 'registration_no']);
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
            $table->string('registration_no')->nullable();
            $table->string('section_card_no')->nullable();
            $table->index('registration_no', 'persons_registration_no_index');
            $table->index('section_card_no', 'persons_section_card_no_index');
        });
    }
};
