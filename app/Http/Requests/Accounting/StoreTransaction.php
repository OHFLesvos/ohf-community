<?php

namespace App\Http\Requests\Accounting;

use App\Models\Accounting\MoneyTransaction;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Setting;

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
            'wallet_id' => [
                isset($this->transaction) ? 'nullable' : 'required',
                'exists:accounting_wallets,id',
            ],
            'receipt_no' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $exists = MoneyTransaction::query()
                        ->when(
                            $this->transaction,
                            fn ($qry) => $qry->where('wallet_id', $this->transaction->wallet_id)
                                ->where('id', '!=', $this->transaction->id),
                            fn ($qry) => $qry->where('wallet_id', $this->wallet_id),
                        )
                        ->where('receipt_no', $value)
                        ->exists();
                    if ($exists) {
                        $fail(__('validation.unique', ['attribute' => __('accounting.receipt_no')]));
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
            'category' => [
                'required',
                Setting::has('accounting.transactions.categories') ? Rule::in(Setting::get('accounting.transactions.categories')) : null,
            ],
            'project' => [
                'nullable',
                Setting::has('accounting.transactions.projects') ? Rule::in(Setting::get('accounting.transactions.projects')) : null,
            ],
            'description' => [
                'required',
            ],
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
                $validator->errors()->add('controlled_at', __('accounting.cannot_update_already_controlled_transaction'));
            }
        });
    }
}
