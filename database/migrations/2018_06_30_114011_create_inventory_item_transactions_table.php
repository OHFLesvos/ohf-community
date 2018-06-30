<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_item_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('storage_id');
            $table->string('item');
            $table->integer('quantity');
            $table->date('expiration_date')->nullable();
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->timestamps();
            $table->foreign('storage_id')->references('id')->on('inventory_storages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_item_transactions');
    }
}
