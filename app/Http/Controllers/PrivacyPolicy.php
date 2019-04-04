<?php

namespace App\Http\Controllers;

use Michelf\MarkdownExtra;

class PrivacyPolicy extends Controller
{
    function userPolicy() {
        $markdown = file_get_contents(base_path() . '/resources/lang/' .  \App::getLocale() . '/user-privacy-policy.md');
        $content = MarkdownExtra::defaultTransform($markdown);
        return view('auth.privacy', [
                'content' => $content,
            ]
        );
    }
}
