<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudget extends FormRequest
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
            'name' => [
                'required',
                isset($this->budget)
                    ? Rule::unique('accounting_budgets')->ignore($this->budget->id)
                    : Rule::unique('accounting_budgets'),
            ],
            'description' => [
                'nullable',
            ],
            'agreed_amount' => [
                'numeric',
                'min:0',
            ],
            'initial_amount' => [
                'numeric',
                'min:0',
            ],
            'donor_id' => [
                'nullable',
                'exists:donors,id',
            ],
            'closed_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}
