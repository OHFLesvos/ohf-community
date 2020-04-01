<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Gate;

class ReportingWidget implements Widget
{
    public function authorize(): bool
    {
        return Gate::allows('view-reports') && $this->getAvailableReports()->count() > 0;
    }

    public function view(): string
    {
        return 'dashboard.widgets.reports';
    }

    public function args(): array
    {
        return [
            'reports' => $this->getAvailableReports()->toArray(),
        ];
    }

    private function getAvailableReports()
    {
        return collect(config('reporting.reports'))
            ->filter(fn ($e) => $e['featured'] && Gate::allows($e['gate']))
            ->map(fn ($item, $key) => (object) [
                'url' => route($item['route']),
                'name' => __('reporting.' . $key),
            ]);
    }
}
