<?php

namespace App\Settings\Accounting;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class TransactionCurrency extends BaseSettingsField
{
    public function section(): string
    {
        return 'accounting';
    }

    public function label(): string
    {
        return __('Currency');
    }

    public function defaultValue()
    {
        return null;
    }

    public function formType(): string
    {
        return 'select';
    }

    public function formList(): array
    {
        return collect(config('currencies'))->map(fn ($e) => $e[0])->toArray();
    }

    public function formPlaceholder(): string
    {
        return __('No currency');
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-accounting');
    }
}
