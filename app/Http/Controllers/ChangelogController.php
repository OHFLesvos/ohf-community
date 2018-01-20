<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Michelf\MarkdownExtra;

class ChangelogController extends Controller
{
    public function index() {
        $markdown = file_get_contents(base_path().'/Changelog.md');
        $content = MarkdownExtra::defaultTransform($markdown);
        $content = preg_replace('/^<h1>.+<\/h1>/', '', $content);
        return view('changelog', [
            'changelog'=> $content,
        ]);
    }
}
