<?php

namespace App\Http\Requests\Fundraising;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonor extends FormRequest
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
            'first_name' => [
                'required_without_all:last_name,company',
            ],
            'last_name' => [
                'required_without_all:first_name,company',
            ],
            'company' => [
                'required_without_all:first_name,last_name',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'country_name' => [
                'nullable',
                'country_name',
            ],
            'language' => [
                'nullable',
                'language_name',
            ]
        ];
    }

}
