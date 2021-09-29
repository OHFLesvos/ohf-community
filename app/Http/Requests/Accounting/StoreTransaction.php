<?php

namespace App\Http\Requests\Accounting;

use App\Models\Accounting\Budget;
use App\Models\Accounting\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransaction extends FormRequest
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
            'wallet' => [
                !isset($this->transaction) ? 'required' : 'nullable',
                'exists:accounting_wallets,id',
            ],
            'receipt_no' => [
                isset($this->transaction) ? 'required' : 'nullable',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $exists = Transaction::query()
                        ->when(
                            $this->transaction,
                            fn ($qry) => $qry->forWallet($this->transaction->wallet)
                                ->where('id', '!=', $this->transaction->id),
                            fn ($qry) => $qry->where('wallet_id', $this->wallet),
                        )
                        ->where('receipt_no', $value)
                        ->exists();
                    if ($exists) {
                        $fail(__('validation.unique', ['attribute' => __('Receipt No.')]));
                    }
                },
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::today(),
            ],
            'type' => [
                'required',
                Rule::in(['income', 'spending']),
            ],
            'amount' => [
                'required',
                'min:0.05',
            ],
            'receipt_picture' => [
                'array',
            ],
            'receipt_picture.*' => [
                'file',
                'mimetypes:image/*,application/pdf',
            ],
            'attendee' => [
                'nullable',
            ],
            'category_id' => [
                'required',
                'exists:accounting_categories,id',
            ],
            'project_id' => [
                'nullable',
                'exists:accounting_projects,id',
            ],
            'description' => [
                'required',
            ],
            'supplier_id' => [
                'nullable',
                'exists:suppliers,id',
            ],
            'budget_id' => [
                'nullable',
                'exists:accounting_budgets,id',
            ],
            'delete_receipts' => [
                'nullable',
                'array',
            ]
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (optional($this->transaction)->controlled_at !== null) {
                $validator->errors()->add('controlled_at', __('Cannot update already controlled transaction.'));
            }
            if ($this->budget_id !== null) {
                $budget = Budget::find($this->budget_id);
                if ($budget !== null) {
                    if ($budget->closed_at !== null) {
                        $date = new Carbon($this->date);
                        $closed = new Carbon($budget->closed_at);
                        if ($date->gt($closed)) {
                            $validator->errors()->add('date', __('Date must be before or on the budget closing date :date.', ['date' => $closed->toDateString()]));
                        }
                    }
                }
            }
        });
    }
}
