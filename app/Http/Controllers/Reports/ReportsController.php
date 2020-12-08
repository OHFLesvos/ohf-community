<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    private array $reports = [
        [
            'message_key' => 'reporting.monthly_summary',
            'route' => 'reports.people.monthly-summary',
            'icon' => 'book',
            'gate' => 'view-people-reports',
        ],
        [
            'message_key' => 'reporting.people',
            'route' => 'reports.people.people',
            'icon' => 'users',
            'gate' => 'view-people-reports',
        ],
        [
            'message_key' => 'reporting.bank-withdrawals',
            'route' => 'reports.bank.withdrawals',
            'icon' => 'id-card',
            'gate' => 'view-bank-reports',
        ],
        [
            'message_key' => 'reporting.bank-visitors',
            'route' => 'reports.bank.visitors',
            'icon' => 'users',
            'gate' => 'view-bank-reports',
        ],
        [
            'message_key' => 'reporting.community_volunteers',
            'route' => 'reports.cmtyvol.report',
            'icon' => 'chart-bar',
            'gate' => 'view-community-volunteer-reports',
        ],
        [
            'message_key' => 'reporting.fundraising',
            'url' => '/fundraising/report',
            'icon' => 'donate',
            'gate' => 'view-fundraising-reports',
        ],
        [
            'message_key' => 'reporting.library',
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
                ->map(fn ($report) => [
                    'label' => __($report['message_key']),
                    'icon' => $report['icon'],
                    'url' => isset($report['route']) ? route($report['route']) : $report['url'],
                ]),
        ]);
    }
}
