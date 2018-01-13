<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMotherToPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->integer('mother_id')->nullable()->unsigned();
            $table->integer('father_id')->nullable()->unsigned();
            $table->foreign('mother_id')->references('id')->on('persons')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('father_id')->references('id')->on('persons')->onDelete('set null')->onUpdate('cascade');
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
            $table->dropForeign(['mother_id']);
            $table->dropForeign(['father_id']);
            $table->dropColumn('mother_id');
            $table->dropColumn('father_id');
        });
    }
}
