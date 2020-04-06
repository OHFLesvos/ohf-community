<?php

namespace App\Settings\Bank;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class FrequentVisitorWeeks extends BaseSettingsField
{
    public function section(): string
    {
        return 'bank';
    }

    public function label(): string
    {
        return __('people.frequent_visitors') . ': ' . __('people.number_of_weeks');
    }

    public function defaultValue()
    {
        return config('bank.frequent_visitor_weeks');
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

    public function formHelp(): ?string
    {
        return __('bank.frequent_visitors_explanation');
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-bank');
    }
}
