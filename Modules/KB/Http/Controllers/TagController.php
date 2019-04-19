<?php

namespace Modules\KB\Http\Controllers;

use App\Tag;
use App\Http\Controllers\Controller;

use Modules\KB\Entities\WikiArticle;
use Modules\KB\Util\ArticleFormat;
use Modules\KB\Util\ArticlePdfExport;

class TagController extends Controller
{
    public function tags() {
        $this->authorize('list', WikiArticle::class);

        return view('kb::tags', [
            'tags' => Tag::has('wikiArticles')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function tag(Tag $tag) {
        $this->authorize('list', WikiArticle::class);

        return view('kb::tag', [
            'articles' => $tag->wikiArticles()
                ->orderBy('title')
                ->paginate(50),
            'tag' => $tag,
        ]);
    }

    public function pdf(Tag $tag) {
        $this->authorize('list', WikiArticle::class);

        $content = '<h1>' . $tag->name . '</h1>' . $tag->wikiArticles()
            ->orderBy('title')
            ->get()
            ->map(function($article){
                return '<h2>' . $article->title . '</h2>' . ArticleFormat::formatContent($article->content);
            })
            ->implode('<hr>');
        ArticlePdfExport::createPDF($tag->name, $content);
    }

}
