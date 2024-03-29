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
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('has_article_mgmt')->default(false)->after('enable_in_bank');
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable()->after('name');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('has_article_mgmt');
        });
    }
};
