<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreBudget extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('accounting_budgets')
                    ->when(isset($this->budget), fn (Unique $rule) => $rule->ignore($this->budget->id)),
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
