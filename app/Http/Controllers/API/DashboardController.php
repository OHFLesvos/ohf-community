<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\View\Widgets\Widget;

class DashboardController extends Controller
{
    protected $dashboardWidgets = [
        \App\View\Widgets\VisitorsWidget::class,
        \App\View\Widgets\CommunityVolunteersWidget::class,
        \App\View\Widgets\AccountingWidget::class,
        \App\View\Widgets\FundraisingWidget::class,
        \App\View\Widgets\UsersWidget::class,
    ];

    public function __invoke()
    {
        return response()->json([
            'data' => collect($this->dashboardWidgets)
                ->map(fn ($clazz) => new $clazz())
                ->filter(fn ($widget) => $widget instanceof Widget)
                ->filter(fn ($widget) => $widget->authorize())
                ->mapWithKeys(fn ($widget) => [$widget->key() => $widget->data()]),
        ]);
    }
}
