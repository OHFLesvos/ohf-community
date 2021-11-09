<?php

namespace App\Http\Requests\Visitors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisitor extends FormRequest
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
            'id_number' => [
                'required',
            ],
            'gender' => [
                'required',
                Rule::in(['male', 'female', 'other']),
            ],
            'date_of_birth' => [
                'required',
                'date',
            ],
            'nationality' => [
                'nullable',
            ],
            'living_situation' => [
                'nullable',
            ],
            'anonymized' => [
                'boolean',
            ],
        ];
    }
}
