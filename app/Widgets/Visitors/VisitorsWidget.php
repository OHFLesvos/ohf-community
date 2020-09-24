<?php

namespace App\Widgets\Visitors;

use App\Models\Visitors\Visitor;
use App\Widgets\Widget;
use Illuminate\Support\Facades\Gate;

class VisitorsWidget implements Widget
{
    public function authorize(): bool
    {
        return Gate::allows('register-visitors');
    }

    public function view(): string
    {
        return 'visitors.dashboard.widgets.visitors';
    }

    public function args(): array
    {
        return [
            'current_visitors' => Visitor::query()
                ->whereNull('left_at')
                ->whereDate('entered_at', today())
                ->count(),
            'todays_visitors' => Visitor::query()
                ->whereDate('entered_at', today())
                ->count(),
        ];
    }
}
