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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('place_id')->nullable()->after('address');
            $table->string('website')->nullable()->after('email');
            $table->string('tax_office')->nullable()->after('tax_number');
            $table->text('remarks')->nullable()->after('iban');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('place_id');
            $table->dropColumn('website');
            $table->dropColumn('tax_office');
            $table->dropColumn('remarks');
        });
    }
};
