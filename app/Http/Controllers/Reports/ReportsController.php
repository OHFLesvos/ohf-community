<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    private function reports(): array
    {
        return [
            [
                'label' => __('app.community_volunteers'),
                'route' => 'reports.cmtyvol.report',
                'icon' => 'chart-bar',
                'gate' => 'view-community-volunteer-reports',
            ],
            [
                'label' => __('app.fundraising'),
                'route' => 'reports.fundraising.donations',
                'icon' => 'donate',
                'gate' => 'view-fundraising-reports',
            ],
            [
                'label' => __('app.visitor_checkins'),
                'route' => 'reports.visitors.checkins',
                'icon' => 'door-open',
                'gate' => 'register-visitors',
            ],
        ];
    }

    public function index(Request $request)
    {
        $this->authorize('view-reports');

        return view('reports.index', [
            'reports' => collect($this->reports())
                ->filter(fn ($report) => $request->user()->can($report['gate']))
                ->map(fn ($report) => [
                    'label' => $report['label'],
                    'icon' => $report['icon'],
                    'url' => isset($report['route']) ? route($report['route']) : $report['url'],
                ]),
        ]);
    }
}
