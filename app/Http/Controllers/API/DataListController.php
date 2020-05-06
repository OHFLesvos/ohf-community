<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataListController extends Controller
{
    public function countries(Request $request)
    {
        $request->validate([
            'locale' => [
                'nullable',
                'alpha',
                'size:2',
            ]
        ]);

        return localized_country_names($request->input('locale'));
    }

    public function languages(Request $request)
    {
        $request->validate([
            'locale' => [
                'nullable',
                'alpha',
                'size:2',
            ]
        ]);

        return localized_language_names($request->input('locale'));
    }
}
