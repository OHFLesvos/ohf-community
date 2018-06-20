<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
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
            'receipt_no' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'receipt_picture' => [
                'nullable',
                'image',
            ],
            'beneficiary' => [
                'required',
            ],
            'project' => [
                'required',
            ],
            'description' => [
                'required',
            ],
        ];
    }
}
