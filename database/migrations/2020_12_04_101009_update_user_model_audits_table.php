<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class UpdateUserModelAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Set the user_type value and keep the timestamp values.
        DB::table('audits')->update([
            'user_type'  => User::class,
            'created_at' => DB::raw('created_at'),
            'updated_at' => DB::raw('updated_at'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
