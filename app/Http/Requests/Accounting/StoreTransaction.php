<?php

namespace App\Http\Requests\Accounting;

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
                'nullable',
                'image',
            ],
            'beneficiary' => [
                'required',
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
}
