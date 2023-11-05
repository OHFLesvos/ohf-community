<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Str;

class ChangelogController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()
            ->json([
                ['content' => Str::markdown(file_get_contents(base_path('Changelog.md')))],
            ]);
    }
}
