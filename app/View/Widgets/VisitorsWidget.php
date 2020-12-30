<?php

namespace App\View\Widgets;

use App\Models\Visitors\Visitor;
use Illuminate\Support\Facades\Gate;

class VisitorsWidget implements Widget
{
    public function authorize(): bool
    {
        return Gate::allows('register-visitors');
    }

    public function render()
    {
        return view('widgets.visitors', [
            'current_visitors' => Visitor::query()
                ->whereNull('left_at')
                ->whereDate('entered_at', today())
                ->count(),
            'todays_visitors' => Visitor::query()
                ->whereDate('entered_at', today())
                ->count(),
        ]);
    }
}
