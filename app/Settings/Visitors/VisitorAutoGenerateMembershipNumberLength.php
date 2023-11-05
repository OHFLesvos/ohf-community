<?php

namespace App\Settings\Visitors;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class VisitorAutoGenerateMembershipNumberLength extends BaseSettingsField
{
    public function section(): string
    {
        return 'visitors';
    }

    public function label(): string
    {
        return __('Auto-generated membership number length');
    }

    public function defaultValue()
    {
        return 5;
    }

    public function formType(): string
    {
        return 'number';
    }

    public function formArgs(): ?array
    {
        return [
            'min' => 3,
        ];
    }

    public function authorized(): bool
    {
        return Gate::allows('register-visitors');
    }
}
