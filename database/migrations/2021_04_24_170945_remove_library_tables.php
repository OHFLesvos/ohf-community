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
        Schema::dropIfExists('library_lendings');
        Schema::dropIfExists('library_books');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('library_books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('language_code', 2)->nullable();
            $table->string('isbn10')->nullable();
            $table->string('isbn13')->nullable();
            $table->timestamps();
        });

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
};
