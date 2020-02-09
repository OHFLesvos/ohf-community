<?php

namespace App\Http\Requests\People;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePerson extends FormRequest
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
                'max:191',
            ],
            'family_name' => [
                'required',
                'max:191',
            ],
            'gender' => [
                'required',
                'in:m,f',
            ],
            'nationality' => [
				'nullable',
				'max:191',
				Rule::in(\Countries::getList('en')),
			],
            'languages' => [
                'max:191',
            ],
            'remarks' => [
                'max:191',
            ],
            'date_of_birth' => [
                'nullable',
                'date',
            ],
        ];
    }
}
