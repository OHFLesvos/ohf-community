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

}
