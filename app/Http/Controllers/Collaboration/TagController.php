<?php

namespace App\Http\Controllers\Collaboration;

use App\Tag;
use App\Http\Controllers\Controller;
use App\Util\Collaboration\ArticleFormat;
use App\Util\Collaboration\ArticlePdfExport;

use Illuminate\Support\Facades\Gate;

class TagController extends Controller
{
    public function tags() {
        $this->authorize('list', Tag::class);

        return view('collaboration.kb.tags', [
            'tags' => Tag::has('wikiArticles')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function tag(Tag $tag) {
        $this->authorize('view', $tag);

        $articles = $tag->wikiArticles()
            ->orderBy('title')
            ->get()
            ->filter(function($a){
                return Gate::allows('view', $a);
            })
            ->paginate(50);

        return view('collaboration.kb.tag', [
            'articles' => $articles,
            'tag' => $tag,
            'has_more_articles' => $tag->wikiArticles()->count() != $articles->total(),
        ]);
    }

    public function pdf(Tag $tag) {
        $this->authorize('view', $tag);

        $articles = $tag->wikiArticles()
            ->orderBy('title')
            ->get();
        $routes = $articles->mapWithKeys(function($article){
            return [route('kb.articles.show', $article) => '#' . $article->slug];
        })->toArray();
        $content = '<h1>' . $tag->name . '</h1>' . $articles->map(function($article) use($routes) {
                // Format content
                $content = ArticleFormat::formatContent($article->content);
                // Replace internal links
                foreach($routes as $k => $v) {
                    $content = str_replace(' href="' . $k . '"', ' href="' . $v . '"', $content);
                }
                return '<a name="' . $article->slug . '"></a><h2>' . $article->title . '</h2>' . $content;
            })
            ->implode('<hr>');
        ArticlePdfExport::createPDF($tag->name, $content);
    }

}
