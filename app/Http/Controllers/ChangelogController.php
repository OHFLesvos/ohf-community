<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Michelf\MarkdownExtra;

class ChangelogController extends Controller
{
    const FILE = 'Changelog.md';

    public function index()
    {
        $markdown = file_get_contents(base_path() . '/' . self::FILE);
        $content = MarkdownExtra::defaultTransform($markdown);
        $content = preg_replace('/^<h1>.+<\/h1>/', '', $content);
        $content = preg_replace('/<h2>(.+)<\/h2>/', '<h3>\1</h3>', $content);
        return view('changelog', [
            'content' => $content,
        ]);
    }
}
