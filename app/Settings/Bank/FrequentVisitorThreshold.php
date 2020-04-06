<?php

namespace App\Settings\Bank;

use App\Http\Controllers\Bank\Reporting\BankReportingController;
use App\Models\People\Person;
use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class FrequentVisitorThreshold extends BaseSettingsField
{
    public function section(): string
    {
        return 'bank';
    }

    public function label(): string
    {
        return __('people.min_number_of_visits');
    }

    public function defaultValue()
    {
        return config('bank.frequent_visitor_threshold');
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

    public function includePost()
    {
        return [
            'bank.settings.frequent_visitors_affected', [
                'current_num_people' => Person::count(),
                'current_num_frequent_visitors' => BankReportingController::getNumberOfFrequentVisitors(),
            ],
        ];
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-bank');
    }
}
