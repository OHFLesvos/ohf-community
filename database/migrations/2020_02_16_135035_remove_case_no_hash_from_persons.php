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
            if (Schema::hasColumn('persons', 'case_no_hash')) {
                $table->dropColumn('case_no_hash');
            }
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
            $table->string('case_no_hash')->nullable()->after('police_no');
            $table->index('case_no_hash', 'persons_case_no_hash_index');
        });
    }
};
