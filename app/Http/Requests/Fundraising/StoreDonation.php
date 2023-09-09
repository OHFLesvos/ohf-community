<?php

namespace App\Http\Requests\Fundraising;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreDonation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                'before_or_equal:'.Carbon::today()->toDateString(),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],
            'exchange_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'currency' => [
                'required',
                'string',
            ],
            'channel' => [
                'required',
                'string',
            ],
            'purpose' => [
                'nullable',
                'string',
            ],
            'reference' => [
                'nullable',
                'string',
            ],
            'budget_id' => [
                'nullable',
                'exists:accounting_budgets,id',
            ],
        ];
    }
}
