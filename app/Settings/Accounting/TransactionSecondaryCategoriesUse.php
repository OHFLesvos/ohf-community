<?php

namespace App\Settings\Accounting;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class TransactionSecondaryCategoriesUse extends BaseSettingsField
{
    public function section(): string
    {
        return 'accounting';
    }

    public function label(): string
    {
        return __('Use secondary categories');
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
        return Gate::allows('configure-accounting');
    }
}
