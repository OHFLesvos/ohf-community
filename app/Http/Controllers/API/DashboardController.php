<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\View\Widgets\Widget;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    private const WIDGETS = [
        \App\View\Widgets\VisitorsWidget::class,
        \App\View\Widgets\CommunityVolunteersWidget::class,
        \App\View\Widgets\AccountingWidget::class,
        \App\View\Widgets\FundraisingWidget::class,
        \App\View\Widgets\UsersWidget::class,
    ];

    public function __invoke(): JsonResponse
    {
        return response()
            ->json([
                'data' => collect(self::WIDGETS)
                    ->map(fn ($clazz) => new $clazz())
                    ->filter(fn ($widget) => $widget instanceof Widget)
                    ->filter(fn ($widget) => $widget->authorize())
                    ->mapWithKeys(fn ($widget) => [$widget->key() => $widget->data()]),
            ]);
    }
}
