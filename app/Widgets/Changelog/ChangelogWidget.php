<?php

namespace App\Widgets\Changelog;

use App\Widgets\Widget;
use Illuminate\Support\Facades\Gate;

class ChangelogWidget implements Widget
{
    public function authorize(): bool
    {
        return Gate::allows('view-changelogs');
    }

    public function view(): string
    {
        return 'changelog.dashboard.widgets.changelog';
    }

    public function args(): array
    {
        return [ ];
    }
}
