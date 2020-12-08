<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    private array $reports = [
        'monthly_summary' => [
            'route' => 'reporting.monthly-summary',
            'icon' => 'book',
            'gate' => 'view-people-reports',
        ],
        'people' => [
            'route' => 'reporting.people',
            'icon' => 'users',
            'gate' => 'view-people-reports',
        ],
        'bank-withdrawals' => [
            'route' => 'reporting.bank.withdrawals',
            'icon' => 'id-card',
            'gate' => 'view-bank-reports',
        ],
        'bank-visitors' => [
            'route' => 'reporting.bank.visitors',
            'icon' => 'users',
            'gate' => 'view-bank-reports',
        ],
        'fundraising' => [
            'url' => '/fundraising/report',
            'icon' => 'donate',
            'gate' => 'view-fundraising-reports',
        ],
        'library' => [
            'url' => '/library/report',
            'icon' => 'book',
            'gate' => 'operate-library',
        ],
    ];

    public function index(Request $request)
    {
        $this->authorize('view-reports');

        return view('reports.index', [
            'reports' => collect($this->reports)
                ->filter(fn ($report) => $request->user()->can($report['gate']))
                ->map(fn ($report, $key) => [
                    'label' => __('reporting.' . $key),
                    'icon' => $report['icon'],
                    'url' => isset($report['route']) ? route($report['route']) : $report['url'],
                ]),
        ]);
    }
}
