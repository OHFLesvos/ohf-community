<?php

namespace App\Http\Controllers;

use App\Support\Facades\DashboardWidgets;

use Illuminate\Http\Request;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $widgets = DashboardWidgets::collection()
            ->filter(function($w) {
                return $w->authorize();
            })
            ->map(function($w){
                return view($w->view(), $w->args())->render();
            });

        return view('welcome', [
            'widgets' => $widgets,
        ]);
    }
}
