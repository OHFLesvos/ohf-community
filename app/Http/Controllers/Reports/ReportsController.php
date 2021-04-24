<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    private array $reports = [
        [
            'message_key' => 'reports.monthly_summary',
            'route' => 'reports.people.monthly-summary',
            'icon' => 'book',
            'gate' => 'view-people-reports',
        ],
        [
            'message_key' => 'reports.people',
            'route' => 'reports.people.people',
            'icon' => 'users',
            'gate' => 'view-people-reports',
        ],
        [
            'message_key' => 'reports.bank-withdrawals',
            'route' => 'reports.bank.withdrawals',
            'icon' => 'id-card',
            'gate' => 'view-bank-reports',
        ],
        [
            'message_key' => 'reports.bank-visitors',
            'route' => 'reports.bank.visitors',
            'icon' => 'users',
            'gate' => 'view-bank-reports',
        ],
        [
            'message_key' => 'reports.community_volunteers',
            'route' => 'reports.cmtyvol.report',
            'icon' => 'chart-bar',
            'gate' => 'view-community-volunteer-reports',
        ],
        [
            'message_key' => 'reports.fundraising',
            'route' => 'reports.fundraising.donations',
            'icon' => 'donate',
            'gate' => 'view-fundraising-reports',
        ],
        [
            'message_key' => 'reports.visitor_checkins',
            'route' => 'reports.visitors.checkins',
            'icon' => 'door-open',
            'gate' => 'register-visitors',
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
