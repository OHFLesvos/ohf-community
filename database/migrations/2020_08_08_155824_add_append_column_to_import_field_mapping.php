<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppendColumnToImportFieldMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_field_mappings', function (Blueprint $table) {
            $table->boolean('append')->default(false)->after('to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_field_mappings', function (Blueprint $table) {
            $table->dropColumn(['append']);
        });
    }
}
