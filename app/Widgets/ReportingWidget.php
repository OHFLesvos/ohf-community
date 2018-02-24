<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;

class ReportingWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('view-reports') && $this->getAvailableReports()->count() > 0;
    }

    function view(): string
    {
        return 'dashboard.widgets.reports';
    }

    function args(): array {
        return [ 
            'reports' => $this->getAvailableReports()->toArray(),
        ];
    }

    private function getAvailableReports() {
        return collect(Config::get('reporting.reports'))
            ->filter(function($e){
                return $e['featured'] && Gate::allows($e['gate']);
            })
            ->map(function($e){
                return (object)[
                    'url' => route($e['route']),
                    'name' => $e['name'],
                ];
            });
    }
}