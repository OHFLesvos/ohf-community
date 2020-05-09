<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Michelf\MarkdownExtra;

class PrivacyPolicy extends Controller
{
    public function userPolicy()
    {
        return view('auth.privacy', [
            'content' => self::getContent(),
        ]);
    }

    private static function getContent()
    {
        $locale = App::getLocale();
        if ($locale != '') {
            $file_path = base_path() . '/resources/lang/' .  $locale . '/user-privacy-policy.md';
            if (is_file($file_path)) {
                $markdown = file_get_contents($file_path);
                return MarkdownExtra::defaultTransform($markdown);
            }
        }
        return null;
    }
}
