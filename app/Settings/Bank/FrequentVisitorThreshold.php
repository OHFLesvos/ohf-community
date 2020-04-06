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
        return __('people.frequent_visitors') . ': ' . __('people.min_number_of_visits');
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

    public function formHelp(): ?string
    {
        $current_num_people = Person::count();
        $current_num_frequent_visitors = BankReportingController::getNumberOfFrequentVisitors();
        return __('bank.frequent_visitors_affected', [
            'freq' => $current_num_frequent_visitors,
            'total' => $current_num_people,
            'percentage' => round($current_num_frequent_visitors/$current_num_people * 100),
        ]);
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-bank');
    }
}
