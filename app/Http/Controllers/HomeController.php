<?php

namespace App\Http\Controllers;

use App\View\Widgets\Widget;

class HomeController extends Controller
{
    protected $dashboardWidgets = [
        \App\View\Widgets\VisitorsWidget::class,
        \App\View\Widgets\CommunityVolunteersWidget::class,
        \App\View\Widgets\AccountingWidget::class,
        \App\View\Widgets\FundraisingWidget::class,
        \App\View\Widgets\PeopleWidget::class,
        \App\View\Widgets\BankWidget::class,
        \App\View\Widgets\ShopWidget::class,
        \App\View\Widgets\LibraryWidget::class,
        \App\View\Widgets\KBWidget::class,
        \App\View\Widgets\UsersWidget::class,
    ];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $widgets = collect($this->dashboardWidgets)
            ->map(fn ($clazz) => new $clazz())
            ->filter(fn ($widget) => $widget instanceof Widget)
            ->filter(fn ($widget) => $widget->authorize())
            ->map(fn ($widget) => $widget->render()->render());

        return view('welcome', [
            'widgets' => $widgets,
        ]);
    }
}
