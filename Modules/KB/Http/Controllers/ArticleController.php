<?php

namespace Modules\KB\Http\Controllers;

use App\Tag;
use App\Http\Controllers\Controller;

use Modules\KB\Entities\WikiArticle;
use Modules\KB\Http\Requests\StoreArticle;
use Modules\KB\Util\ArticleFormat;
use Modules\KB\Util\ArticlePdfExport;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request) {
        $this->authorize('list', WikiArticle::class);

        $request->validate([
            'order' => [
                'nullable',
                'in:popularity,recent',
            ]
        ]);

        $query = WikiArticle::query();
        if ($request->order == 'popularity') {
            $query->leftJoin('kb_article_views', function($join){
                    $join->on('kb_article_views.viewable_id', '=', 'kb_articles.id')
                        ->where('kb_article_views.viewable_type', WikiArticle::class);
                })
                ->orderBy('kb_article_views.value', 'desc')
                ->select('kb_articles.*');
        } else if ($request->order == 'recent') {
            $query->orderBy('updated_at', 'desc');
        }

        return view('kb::articles.index', [
            'articles' => $query->orderBy('title')
                ->paginate(50),
            'order' => $request->order,
        ]);
    }

    public function create(Request $request) {
        $this->authorize('create', WikiArticle::class);

        return view('kb::articles.create', [
            'title' => $request->title,
            'tag_suggestions' => self::getTagSuggestions(),
        ]);
    }

    public function store(StoreArticle $request) {
        $this->authorize('create', WikiArticle::class);

        $article = new WikiArticle();
        $article->title = $request->title;
        $article->public = $request->has('public');
        $article->content = $request->content;
        $article->save();

        $article->syncTags(self::splitTags($request->tags));

        return redirect()->route('kb.articles.show', $article)
            ->with('info', __('kb::wiki.article_created'));
    }

    public function show(WikiArticle $article, Request $request) {
        $this->authorize('view', $article);

        // Set articles as viewed, but count only for first time in session
        if ($request->session()->has('kb.articles_viewed')) {
            $articles_viewed = $request->session()->get('kb.articles_viewed');
            if (!(is_array($articles_viewed) && in_array($article->id, $articles_viewed))) {
                $request->session()->push('kb.articles_viewed', $article->id);
                $article->setViewed();
            }
        } else {
            $request->session()->push('kb.articles_viewed', $article->id);
            $article->setViewed();
        }

        // Format article
        $article->content = ArticleFormat::formatContent($article->content);

        return view('kb::articles.show', [
            'article' => $article
        ]);
    }

    public function edit(WikiArticle $article) {
        $this->authorize('update', $article);

        return view('kb::articles.edit', [
            'article' => $article,
            'tag_suggestions' => self::getTagSuggestions(),
        ]);
    }

    public function update(WikiArticle $article, StoreArticle $request) {
        $this->authorize('update', $article);

        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->public = $request->has('public');
        $article->content = $request->content;
        $article->save();

        $article->syncTags(self::splitTags($request->tags));

        return redirect()->route('kb.articles.show', $article)
            ->with('info', __('kb::wiki.article_updated'));
    }

    public function destroy(WikiArticle $article) {
        $this->authorize('delete', $article);

        $article->tags()->detach();

        $article->delete();

        return redirect()->route('kb.index')
            ->with('info', __('kb::wiki.article_deleted'));
    }
    
    private static function splitTags($value): array 
    {
        return collect(json_decode($value))
            ->pluck('value')
            ->unique()
            ->toArray();
    }

    private static function getTagSuggestions()
    {
        return Tag::has('wikiArticles')
            ->orderBy('name')
            ->get()
            ->pluck('name')
            ->toArray();
    }

    public function pdf(WikiArticle $article) {
        $this->authorize('view', $article);

        $content = '<h1>' . $article->title . '</h1>' . ArticleFormat::formatContent($article->content);
        ArticlePdfExport::createPDF($article->title, $content);
    }


}
