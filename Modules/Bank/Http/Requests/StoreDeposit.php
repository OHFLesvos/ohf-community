<?php

namespace Modules\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeposit extends FormRequest
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
            'coupon_type' => [
                'required',
                'numeric',
                'exists:coupon_types,id',
            ],
            'project' => [
                'required',
                'numeric',
                'exists:projects,id',
            ],
            'amount' => [
                'required',
                'numeric',
                'not_in:0',
            ],
            'date' => [
                'required',
                'date',
            ]
        ];
    }
}
