<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Project;
use App\Article;

class AddArticleMgmtToProjects extends Migration
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
        });
    
        // Migrate existing articles, which were by default "kitchen" articles
        $project = Project::where('name', 'Kitchen')->first();
        if ($project == null) {
            $project = new Project();
            $project->name = 'Kitchen';
            $project->has_article_mgmt = true;
            $project->save();
        }
        foreach (Article::get() as $article) {
            $project->articles()->save($article);
        }
    
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable(false)->change();
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
}
