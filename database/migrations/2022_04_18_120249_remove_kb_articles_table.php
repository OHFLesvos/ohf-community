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
        DB::table('kb_articles')->get()->each(function ($article) {
            Storage::put("kb/articles/{$article->slug}.json", json_encode([
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'public' => $article->public,
                'featured' => $article->featured,
                'content' => $article->content,
                'created_at' => $article->created_at,
                'updated_at' => $article->updated_at,
                'views' => DB::table('kb_article_views')
                    ->where('viewable_type', 'App\Models\Collaboration\WikiArticle')
                    ->where('viewable_id', $article->id)
                    ->first()?->value ?? 0
            ], JSON_PRETTY_PRINT));
        });
        Schema::dropIfExists('kb_article_views');
        Schema::dropIfExists('kb_articles');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('kb_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->boolean('public')->default(false);
            $table->boolean('featured')->default(false);
            $table->text('content');
            $table->text('search')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('kb_article_views', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('value');
            $table->unsignedInteger('viewable_id');
            $table->string('viewable_type');
        });
    }
};
