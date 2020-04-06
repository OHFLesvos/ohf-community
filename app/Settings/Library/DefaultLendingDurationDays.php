<?php

namespace App\Settings\Library;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class DefaultLendingDurationDays extends BaseSettingsField
{
    public const DEFAULT_VALUE = 14;

    public function section(): string
    {
        return 'library';
    }

    public function label(): string
    {
        return __('library.default_lending_duration_days_in_days');
    }

    public function defaultValue()
    {
        return self::DEFAULT_VALUE;
    }

    public function formType(): string
    {
        return 'number';
    }

    public function formArgs(): ?array
    {
        return [
            'min' => 1,
        ];
    }

    public function formValidate(): ?array
    {
        return [
            'required',
            'numeric',
            'min:1',
        ];
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-library');
    }
}
