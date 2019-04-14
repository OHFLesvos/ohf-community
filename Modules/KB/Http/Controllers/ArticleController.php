<?php

namespace Modules\KB\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\KB\Entities\WikiArticle;
use Modules\KB\Http\Requests\StoreArticle;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        ]);
    }

    public function store(StoreArticle $request) {
        $this->authorize('create', WikiArticle::class);

        $article = new WikiArticle();
        $article->title = $request->title;
        $article->content = $request->content;
        $article->save();

        $article->syncTags(self::splitTags($request->tags));

        return redirect()->route('kb.articles.show', $article)
            ->with('info', __('kb::wiki.article_created'));
    }

    private static function splitTags($value) {
        return array_unique(preg_split('/\s*,\s*/', $value, -1, PREG_SPLIT_NO_EMPTY));
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

        //
        // Format article
        //
        // Open links in new window
        $article->content = preg_replace('/<a /', '<a target="_blank" ', $article->content);

        // Replace phone mumber tags
        $article->content = preg_replace('/tel:([0-9+ ]+[0-9])/', '<a href="tel:\1">\1</a>', $article->content);

        // Create links from e-mail addresses
        $article->content = emailize($article->content);

        // Create emedded maps
        $article->content = preg_replace('/map:"(.+)"/', '<iframe style="width: 100%" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=' . env('GOOGLE_MAPS_API_KEY') . '&q=\1" allowfullscreen></iframe>', $article->content);

        // Link to other articles
        $article->content = preg_replace_callback("/(\[\[([a-z0-9-]+)\]\])/", function($matches){
            $article = WikiArticle::where('slug', $matches[2])->first();
            if ($article != null) {
                return '<a href="' . route('kb.articles.show', $article) . '">' . $article->title . '</a>';
            }
            return '<a href="' . route('kb.articles.create', ['title' => Str::title(str_replace('-', ' ', $matches[2])) ]) . '" class="text-danger">' . $matches[2] . '</a>';
        }, $article->content);

        return view('kb::articles.show', [
            'article' => $article
        ]);
    }

    public function edit(WikiArticle $article) {
        $this->authorize('update', $article);

        return view('kb::articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(WikiArticle $article, StoreArticle $request) {
        $this->authorize('update', $article);

        $article->title = $request->title;
        $article->content = $request->content;
        $article->save();

        $article->syncTags(self::splitTags($request->tags));

        return redirect()->route('kb.articles.show', $article)
            ->with('info', __('kb::wiki.article_updated'));
    }

    public function destroy(WikiArticle $article) {
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()->route('kb.index')
            ->with('info', __('kb::wiki.article_deleted'));
    }

}
