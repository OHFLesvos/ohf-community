<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DataListController extends Controller
{
    public function countries(): array
    {
        return Countries::getList('en');
    }

    public function localizedCountries(Request $request): Collection
    {
        $request->validate([
            'locale' => [
                'nullable',
                'alpha',
                'size:2',
            ],
        ]);

        return localized_country_names($request->input('locale'));
    }

    public function localizedLanguages(Request $request): Collection
    {
        $request->validate([
            'locale' => [
                'nullable',
                'alpha',
                'size:2',
            ],
        ]);

        return localized_language_names($request->input('locale'));
    }
}
