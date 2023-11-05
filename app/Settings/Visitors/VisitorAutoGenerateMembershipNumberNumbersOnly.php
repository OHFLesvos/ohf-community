<?php

namespace App\Settings\Visitors;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class VisitorAutoGenerateMembershipNumberNumbersOnly extends BaseSettingsField
{
    public function section(): string
    {
        return 'visitors';
    }

    public function label(): string
    {
        return __('Use only numbers in auto-generated membership number');
    }

    public function defaultValue()
    {
        return false;
    }

    public function formType(): string
    {
        return 'checkbox';
    }

    public function authorized(): bool
    {
        return Gate::allows('register-visitors');
    }
}
