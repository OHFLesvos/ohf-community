<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoargeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('container_id');
            $table->string('name');
            $table->integer('amount');
            $table->date('expiration_date')->nullable();
            $table->string('source')->nullable();
            $table->string('destination')->nullable();
            $table->timestamps();
            $table->foreign('container_id')->references('id')->on('storage_containers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_transactions');
    }
}
