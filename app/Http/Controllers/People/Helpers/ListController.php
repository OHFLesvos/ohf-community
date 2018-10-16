<?php

namespace App\Http\Controllers\People\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Person;

class ListController extends Controller
{
    public function index(Request $request) {
        return view('people.helpers.index', [
            'persons' => Person::where('worker', false)->orderBy('name')->get(),
        ]);
    }
}
