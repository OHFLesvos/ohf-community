<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategory extends FormRequest
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
            ],
            'description' => [
                'nullable',
            ],
            'enabled' => 'boolean',
            'parent_id' => [
                'nullable',
                Rule::exists('accounting_categories', 'id'),
                isset($this->category) ? Rule::notIn($this->category->id) : null,
            ]
        ];
    }
}
