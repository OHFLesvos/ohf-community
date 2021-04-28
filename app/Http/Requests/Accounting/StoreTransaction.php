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
            'receipt_no' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $exists = MoneyTransaction::query()
                        ->when(
                            $this->transaction,
                            fn ($qry) => $qry->forWallet($this->transaction->wallet)
                                ->where('id', '!=', $this->transaction->id),
                            fn ($qry) => $qry->forWallet($this->wallet),
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
                $validator->errors()->add('controlled_at', __('Kann bereits kontrollierte Transaktion nicht ändern.'));
            }
        });
    }
}
