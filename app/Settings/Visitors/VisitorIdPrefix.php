<?php

namespace App\Settings\Visitors;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class VisitorIdPrefix extends BaseSettingsField
{
    public function section(): string
    {
        return 'visitors';
    }

    public function label(): string
    {
        return __('ID Number prefix');
    }

    public function defaultValue()
    {
        return '';
    }

    public function formType(): string
    {
        return 'text';
    }

    public function authorized(): bool
    {
        return Gate::allows('register-visitors');
    }
}
