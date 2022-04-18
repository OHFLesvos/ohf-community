<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        // workaround for laravels limitation to change tables with an enum
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Initially, the following fields did not have a default value
        // The original migration classes have been adapted since
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->change();
        });

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->boolean('booked')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(null)->change();
        });

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->boolean('booked')->default(null)->change();
        });
    }
};
