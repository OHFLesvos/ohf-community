<?php

namespace App\Http\Controllers;

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
        $widgetClasses = [
            \App\Widgets\BankWidget::class,
            \App\Widgets\PersonsWidget::class,
            \App\Widgets\ShopWidget::class,
            \App\Widgets\BarberShopWidget::class,
            \App\Widgets\LibraryWidget::class,
            \App\Widgets\HelpersWidget::class,
            \App\Widgets\WikiArticlesWidget::class,
            \Modules\Accounting\Widgets\TransactionsWidget::class,
            \App\Widgets\InventoryWidget::class,
            \App\Widgets\DonorsWidget::class,
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
