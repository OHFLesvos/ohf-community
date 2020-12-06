<?php

namespace App\Util\Collaboration;

use App\Models\Collaboration\WikiArticle;
use Illuminate\Support\Str;

class ArticleFormat
{
    public static function formatContent(string $content)
    {
        //
        // Format article
        //
        // Open links in new window
        $content = preg_replace('/<a /', '<a target="_blank" ', $content);

        // Responsive images
        $content = preg_replace('/<img /', '<img class="img-fluid" ', $content);

        // Replace phone mumber tags
        $content = preg_replace('/tel:([0-9+ ]+[0-9])/', '<a href="tel:\1">\1</a>', $content);

        // Create links from e-mail addresses
        $content = emailize($content);

        // Link to other articles
        return preg_replace_callback("/(\[\[([a-z0-9-]+)\]\])/", function ($matches) {
            $article = WikiArticle::where('slug', $matches[2])->first();
            if ($article !== null) {
                return '<a href="' . route('kb.articles.show', $article) . '">' . $article->title . '</a>';
            }
            return '<a href="' . route('kb.articles.create', ['title' => Str::title(str_replace('-', ' ', $matches[2])) ]) . '" class="text-danger">' . $matches[2] . '</a>';
        }, $content);
    }

}
