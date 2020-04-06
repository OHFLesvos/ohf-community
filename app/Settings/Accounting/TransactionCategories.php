<?php

namespace App\Settings\Accounting;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class TransactionCategories extends BaseSettingsField
{
    public function section(): string
    {
        return 'accounting';
    }

    public function label(): string
    {
        return __('app.categories');
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
        return __('app.separate_items_by_newline');
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
