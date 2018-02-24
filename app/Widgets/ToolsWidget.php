<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Gate;

class ToolsWidget implements Widget
{
    function authorize(): bool
    {
        return $this->getAvailableTools()->count() > 0;
    }

    function view(): string
    {
        return 'dashboard.widgets.tools';
    }

    function args(): array {
        return [
            'tools' => $this->getAvailableTools(),
        ];
    }

    private function getAvailableTools() {
        return collect([
                [
                    'route' => 'calendar',
                    'icon' => 'calendar',
                    'name' => 'Calendar / Scheduler',
                    'gate' => 'view-calendar',
                ],
            ])
            ->filter(function($e){
                return Gate::allows($e['gate']);
            });
    }
}