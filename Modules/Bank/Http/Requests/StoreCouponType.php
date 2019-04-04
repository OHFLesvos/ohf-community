<?php

namespace Modules\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponType extends FormRequest
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
            ],
            'icon' => [
                'nullable',
            ],
            'daily_amount' => [
                'required',
                'numeric',
            ],
            'retention_period' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'min_age' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'max_age' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'daily_spending_limit' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'order' => [
                'required',
                'numeric',
                'min:0',
            ],
            'enabled' => [
                'boolean',
            ],
            'returnable' => [
                'boolean',
            ],
        ];
    }
}
