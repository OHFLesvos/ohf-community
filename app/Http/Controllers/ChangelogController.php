<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Michelf\MarkdownExtra;

class ChangelogController extends Controller
{
    public function index()
    {
        $markdown = file_get_contents(base_path('Changelog.md'));

        return view('changelog', [
            'content' => $this->parseMarkdown($markdown),
        ]);
    }

    private function parseMarkdown($markdown)
    {
        $content = MarkdownExtra::defaultTransform($markdown);
        $content = preg_replace('/^<h1>.+<\/h1>/', '', $content);
        $content = preg_replace('/<h2>(.+)<\/h2>/', '<h3>\1</h3>', $content);
        return $content;
    }
}
