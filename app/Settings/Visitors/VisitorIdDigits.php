<?php

namespace App\Settings\Visitors;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class VisitorIdDigits extends BaseSettingsField
{
    public function section(): string
    {
        return 'visitors';
    }

    public function label(): string
    {
        return __('ID Number digits');
    }

    public function defaultValue()
    {
        return 0;
    }

    public function formType(): string
    {
        return 'number';
    }

    public function formHelp(): ?string
    {
        return __('A value of 0 disables automatic visitor ID number generation');
    }

    public function authorized(): bool
    {
        return Gate::allows('register-visitors');
    }
}
