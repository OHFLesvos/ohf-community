<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class PrivacyPolicyController extends Controller
{
    public function __invoke()
    {
        return view('auth.privacy', [
            'content' => $this->getContent(App::getLocale()),
        ]);
    }

    private function getContent($locale)
    {
        if ($locale != '') {
            $file_path = base_path('lang/'.$locale.'/user-privacy-policy.md');
            if (is_file($file_path)) {
                $markdown = file_get_contents($file_path);

                return Str::markdown($markdown);
            }
        }

        return null;
    }
}
