<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('audits', function (Blueprint $table) {
            $table->string('user_type')->nullable();
        });

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
        // There's no turning back
    }
};
