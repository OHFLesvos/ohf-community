<?php

namespace App\Http\Requests\Visitors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Setting;

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
                'nullable',
            ],
            'gender' => [
                'required',
                Rule::in(['male', 'female', 'other']),
            ],
            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:today'
            ],
            'nationality' => [
                'nullable',
                Rule::in(Setting::get('visitors.nationalities', [])),
            ],
            'living_situation' => [
                'nullable',
                Rule::in(Setting::get('visitors.living_situations', [])),
            ],
            'anonymized' => [
                'boolean',
            ],
        ];
    }
}
