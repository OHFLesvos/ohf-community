<?php

namespace Modules\Changelog\Widgets;

use App\Widgets\Widget;

use Illuminate\Support\Facades\Gate;

class ChangelogWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('view-changelogs');
    }

    function view(): string
    {
        return 'changelog::dashboard.widgets.changelog';
    }

    function args(): array {
        return [ ];
    }
}