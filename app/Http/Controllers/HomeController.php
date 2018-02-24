<?php

namespace App\Http\Controllers;

use App\Person;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $widgetClasses = [
            \App\Widgets\PersonsWidget::class,
            \App\Widgets\BankWidget::class,
            \App\Widgets\ReportingWidget::class,
            \App\Widgets\ToolsWidget::class,
            \App\Widgets\UsersWidget::class,
            \App\Widgets\ChangeLogWidget::class,
        ];
        $widgets = [];
        foreach($widgetClasses as $w) {
            $widget = new $w();
            if ($widget->authorize()) {
                $view = view($widget->view(), $widget->args());
                $widgets[] = $view->render();
            }
        }
        return view('welcome', [
            'widgets' => $widgets,
        ]);
    }
}
