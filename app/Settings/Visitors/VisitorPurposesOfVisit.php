<?php

namespace App\Settings\Visitors;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class VisitorPurposesOfVisit extends BaseSettingsField
{
    public function section(): string
    {
        return 'visitors';
    }

    public function label(): string
    {
        return __('Purposes of visit');
    }

    public function defaultValue()
    {
        return '';
    }

    public function formType(): string
    {
        return 'textarea';
    }

    public function formHelp(): ?string
    {
        return __('Separate items by newline');
    }

    public function setter($value)
    {
        return array_unique(array_map(fn ($v) => trim($v), preg_split('/(\s*[,\/|]\s*)|(\s*\n\s*)/', $value)));
    }

    public function getter($value)
    {
        return implode("\n", $value);
    }

    public function authorized(): bool
    {
        return Gate::allows('register-visitors');
    }
}
