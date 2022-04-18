<?php

namespace App\View\Widgets;

use App\Models\Visitors\VisitorCheckin;
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
            'todays_visitors' => VisitorCheckin::query()
                ->whereDate('created_at', today())
                ->count(),
        ]);
    }
}
