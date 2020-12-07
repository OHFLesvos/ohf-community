<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Michelf\MarkdownExtra;

class PrivacyPolicy extends Controller
{
    public function userPolicy()
    {
        return view('auth.privacy', [
            'content' => self::getContent(App::getLocale()),
        ]);
    }

    private static function getContent($locale)
    {
        if ($locale != '') {
            $file_path = resource_path('lang/' .  $locale . '/user-privacy-policy.md');
            if (is_file($file_path)) {
                $markdown = file_get_contents($file_path);
                return MarkdownExtra::defaultTransform($markdown);
            }
        }
        return null;
    }
}
