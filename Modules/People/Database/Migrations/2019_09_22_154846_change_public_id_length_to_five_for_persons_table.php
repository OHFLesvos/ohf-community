<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangePublicIdLengthToFiveForPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('persons')->update(['public_id' => DB::raw('UPPER(SUBSTR(public_id, 1, 6))')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('persons')->update(['public_id' => DB::raw('LOWER(public_id)')]);
    }
}
