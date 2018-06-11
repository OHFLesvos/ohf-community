<?php

namespace App\Http\Controllers\Wiki;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WikiArticle;
use App\Tag;
use App\Http\Requests\Wiki\StoreArticle;
use Michelf\MarkdownExtra;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use OwenIt\Auditing\Models\Audit;

class ArticleController extends Controller
{
    public function index() {
        $this->authorize('list', WikiArticle::class);

        return view('wiki.articles.index', [
            'articles' => WikiArticle::orderBy('title')->paginate(50),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function tag(Tag $tag) {
        $this->authorize('list', WikiArticle::class);

        return view('wiki.articles.tag', [
            'articles' => $tag->wikiArticles()->orderBy('title')->paginate(50),
            'tag' => $tag,
        ]);
    }

    public function latestChanges() {
        $this->authorize('list', WikiArticle::class);

        return view('wiki.articles.latest_changes', [
            'audits' =>  Audit
                ::where('auditable_type', 'App\WikiArticle')
                ->orderBy('created_at', 'DESC')
                ->paginate(),
        ]);
    }

    public function create() {
        $this->authorize('create', WikiArticle::class);

        return view('wiki.articles.create', [
        ]);
    }

    public function store(StoreArticle $request) {
        $this->authorize('create', WikiArticle::class);

        $article = new WikiArticle();
        $article->title = $request->title;
        $article->content = $request->content;
        $article->save();

        $tags = self::splitTags($request->tags);
        foreach($tags as $tag_str) {
            $tag = Tag::where('name', $tag_str)->first();
            if ($tag != null) {
                $article->tags()->attach($tag);
            } else {
                $tag = new Tag();
                $tag->name = $tag_str;
                $article->tags()->save($tag);
            }
        }

        return redirect()->route('wiki.articles.show', $article)
            ->with('info', __('wiki.article_created'));
    }

    private static function splitTags($value) {
        return array_unique(preg_split('/\s*,\s*/', $value, -1, PREG_SPLIT_NO_EMPTY));
    }

    public function show(WikiArticle $article) {
        $this->authorize('view', $article);

        $article->content = MarkdownExtra::defaultTransform($article->content);
        $article->content = preg_replace('/<a /', '<a target="_blank" ', $article->content);
        $article->content = preg_replace("/(\w|<\/a>|<\/em>|<\/strong>)\n/", '\1<br>', $article->content);
        $article->content = preg_replace('/tel:([0-9+ ]+[0-9])/', '<a href="tel:\1">\1</a>', $article->content);
        $article->content = emailize($article->content);
        $article->content = preg_replace('/map:"(.+)"/', '<iframe style="width: 100%" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=' . env('GOOGLE_MAPS_API_KEY') . '&q=\1" allowfullscreen></iframe>', $article->content);
        $article->content = preg_replace_callback("/(\[\[([a-z0-9-]+)\]\])/", function($matches){
            $article = WikiArticle::where('slug', $matches[2])->first();
            if ($article != null) {
                return '<a href="' . route('wiki.articles.show', $article) . '">' . $article->title . '</a>';
            }
            return $matches[1];
        }, $article->content);

        return view('wiki.articles.show', [
            'article' => $article
        ]);
    }

    public function edit(WikiArticle $article) {
        $this->authorize('update', $article);

        return view('wiki.articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(WikiArticle $article, StoreArticle $request) {
        $this->authorize('update', $article);

        $article->title = $request->title;
        $article->content = $request->content;
        $article->save();

        $tags = self::splitTags($request->tags);
        $tag_ids = [];
        foreach($tags as $tag_str) {
            $tag = Tag::where('name', $tag_str)->first();
            if ($tag != null) {
                $tag_ids[] = $tag->id;
            } else {
                $tag = new Tag();
                $tag->name = $tag_str;
                $tag->save();
                $tag_ids[] = $tag->id;
            }
        }
        $article->tags()->sync($tag_ids);

        return redirect()->route('wiki.articles.show', $article)
            ->with('info', __('wiki.article_updated'));
    }

    public function destroy(WikiArticle $article) {
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()->route('wiki.articles.index')
            ->with('info', __('wiki.article_deleted'));
    }

}
