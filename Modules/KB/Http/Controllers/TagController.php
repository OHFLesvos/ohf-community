<?php

namespace Modules\KB\Http\Controllers;

use App\Tag;
use App\Http\Controllers\Controller;

use Modules\KB\Entities\WikiArticle;

class TagController extends Controller
{
    public function tags() {
        $this->authorize('list', WikiArticle::class);

        return view('kb::tags', [
            'tags' => Tag::orderBy('name')
                ->get()
                ->filter(function($t){
                    return $t->wikiArticles()->count() > 0;
                }),
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

}
