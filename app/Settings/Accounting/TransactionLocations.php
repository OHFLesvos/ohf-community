<?php

namespace App\Settings\Accounting;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class TransactionLocations extends BaseSettingsField
{
    public function section(): string
    {
        return 'accounting';
    }

    public function label(): string
    {
        return __('Locations');
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
        return preg_split('/(\s*[,\/|]\s*)|(\s*\n\s*)/', $value);
    }

    public function getter($value)
    {
        return implode("\n", $value);
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-accounting');
    }
}
