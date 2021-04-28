<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ChangelogController extends Controller
{
    public function index()
    {
        $markdown = file_get_contents(base_path('Changelog.md'));
        return view('changelog', [
            'content' => Str::markdown($markdown),
        ]);
    }
}
