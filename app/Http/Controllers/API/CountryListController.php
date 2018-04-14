<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\CountriesExtended;

class CountryListController extends Controller
{
    function list(Request $request) {
        $lang = $request->lang ?? 'en';
        $countries = CountriesExtended::getList($lang);
        return response()->json([
            "suggestions" => $countries,
        ]);
    }
    
}
