<?php

namespace App\Http\Controllers;

use App\Support\Facades\DashboardWidgets;

class HomeController extends Controller
{
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
