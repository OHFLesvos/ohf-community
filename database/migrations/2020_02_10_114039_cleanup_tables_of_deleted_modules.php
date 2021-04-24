<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CleanupTablesOfDeletedModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('school_students');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('logistics_offers');
        Schema::dropIfExists('logistics_products');
        Schema::dropIfExists('logistics_suppliers');
        Schema::dropIfExists('points_of_interest');
        Schema::dropIfExists('inventory_item_transactions');
        Schema::dropIfExists('inventory_storages');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ...
    }
}
