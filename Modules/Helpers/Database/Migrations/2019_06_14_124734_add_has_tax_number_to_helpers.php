<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasTaxNumberToHelpers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('helpers', function (Blueprint $table) {
            $table->enum('has_tax_number', ['yes', 'no', 'applied'])->nullable()->default(null)->after('work_leaving_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('helpers', function (Blueprint $table) {
            $table->dropColumn('has_tax_number');
        });
    }
}
