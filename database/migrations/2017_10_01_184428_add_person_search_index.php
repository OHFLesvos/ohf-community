<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonSearchIndex extends Migration
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
		Schema::table('persons', function (Blueprint $table) {
			$table->string('search')->nullable()->change();
			$table->index('search', 'persons_search_index');
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
			$table->dropIndex('persons_search_index');
		});
    }
}
