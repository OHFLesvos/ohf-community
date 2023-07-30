<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PrivacyPolicyController extends Controller
{
    public function __invoke(): View
    {
        return view('auth.privacy', [
            'content' => $this->getContent(App::getLocale()),
        ]);
    }

    private function getContent(string $locale): ?string
    {
        $file_path = base_path('lang/'.$locale.'/user-privacy-policy.md');
        if (! is_file($file_path)) {
            return null;
        }

        $markdown = file_get_contents($file_path);

        return Str::markdown($markdown);
    }
}
