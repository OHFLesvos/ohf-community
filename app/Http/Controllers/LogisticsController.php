<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class LogisticsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        return view('logistics.index', [
            'projects' => Project::orderBy('name')->get(),
        ]);
    }
}
