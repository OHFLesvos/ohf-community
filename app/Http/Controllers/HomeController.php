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
            ->filter(fn ($widget) => $widget->authorize())
            ->map(fn ($widget) => view($widget->view(), $widget->args())->render());

        return view('welcome', [
            'widgets' => $widgets,
        ]);
    }
}
