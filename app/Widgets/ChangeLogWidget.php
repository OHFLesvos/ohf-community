<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Gate;

class ChangeLogWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('view-changelogs');
    }

    function view(): string
    {
        return 'dashboard.widgets.changelog';
    }

    function args(): array {
        return [ ];
    }
}