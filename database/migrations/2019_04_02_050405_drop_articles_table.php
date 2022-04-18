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
        Schema::table('articles', function (Blueprint $table) {
            Schema::dropIfExists('articles');
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('has_article_mgmt');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('project_id');
            $table->string('unit');
            $table->string('type');
            $table->timestamps();
            $table->unique(['name', 'type']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('has_article_mgmt')->default(false)->after('enable_in_bank');
        });
    }
};
