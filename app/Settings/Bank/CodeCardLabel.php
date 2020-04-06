<?php

namespace App\Settings\Bank;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class CodeCardLabel extends BaseSettingsField
{
    public function section(): string
    {
        return 'bank';
    }

    public function labelKey(): string
    {
        return 'people.label_on_code_card';
    }

    public function defaultValue()
    {
        return '';
    }

    public function formType(): string
    {
        return 'text';
    }

    public function formValidate(): ?array
    {
        return [
            'nullable',
        ];
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-bank');
    }
}
