<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWallet extends FormRequest
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
                'string',
                'max:191',
                isset($this->wallet)
                    ? Rule::unique('accounting_wallets')->ignore($this->wallet->id)
                    : Rule::unique('accounting_wallets'),
            ],
            'is_default' => [
                'boolean',
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (isset($this->wallet) && $this->wallet->is_default && ! $this->input('is_default')) {
                $validator->errors()->add('is_default', __('There must be one default wallet.'));
            }
        });
    }
}
