<?php

namespace App\Http\Requests\Fundraising;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreDonation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => [
                'required',
                'date',
                'max:' . Carbon::today()->toDateString(),
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
