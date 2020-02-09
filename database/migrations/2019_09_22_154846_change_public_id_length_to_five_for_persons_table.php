<?php

use App\Models\People\Person;

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
        DB::table('persons')->update(['public_id' => DB::raw('SUBSTR(public_id, 1, ' . Person::PUBLIC_ID_LENGTH . ')')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
