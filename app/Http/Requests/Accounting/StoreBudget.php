<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

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
            ],
            'description' => [
                'nullable',
            ],
            'amount' => [
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
            ]
        ];
    }
}
