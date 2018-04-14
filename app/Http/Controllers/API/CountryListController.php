<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetCountryList;

class CountryListController extends Controller
{
    function suggestions(GetCountryList $request) {
        $lang = $request->lang ?? \App::getLocale();
        $countries = collect(\Countries::getList($lang));

        // Filter by query
        if (isset($request->query()['query'])) {
            $q = $request->query()['query'];
            $countries = $countries
                ->filter(function($e) use($q) {
                    return stristr($e, $q);
                });
        }

        return response()->json(self::suggestions_array($countries));
    }

    public static function suggestions_array($data) {
        return [
            "suggestions" => collect($data)
                ->map(function($v, $k){
                    return [
                        'value' => $v,
                        'data' => $k,
                    ];
                })
                ->values()
                ->toArray(),
            ];
    }

}
