<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UseCategoryInsteadOfProjectsInTransactionsTable extends Migration
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
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('project')->nullable()->change();
        });

        DB::table('money_transactions')
            ->where('category', null)
            ->update([
                'category' => DB::raw('project'),
                'project' => null,
            ]);

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });

        DB::table('money_transactions')
            ->where('project', null)
            ->update([
                'project' => DB::raw('category'),
                'category' => null,
            ]);

        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('project')->nullable(false)->change();
        });
    }
}
