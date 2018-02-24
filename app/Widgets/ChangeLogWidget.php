<?php

namespace App\Widgets;

class ChangeLogWidget implements Widget
{
    function authorize(): bool
    {
        return true;
    }

    function view(): string
    {
        return 'dashboard.widgets.changelog';
    }

    function args(): array {
        return [ ];
    }
}