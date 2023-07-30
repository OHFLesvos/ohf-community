<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('library_lendings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('person_id')->nullable();
            $table->unsignedInteger('book_id');
            $table->date('lending_date');
            $table->date('return_date');
            $table->date('returned_date')->nullable();
            $table->timestamps();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('book_id')->references('id')->on('library_books')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('library_lendings');
    }
};
