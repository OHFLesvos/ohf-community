<?php

namespace Modules\Logistics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupplierRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'category' => 'required',
            'country_name' => [
                'nullable',
                'country_name',
            ],
            'latitude' => [
                'nullable',
                'regex:/-?\d+\.\d+/',
                'required_with:longitude',
            ],
            'longitude' => [
                'nullable',
                'regex:/-?\d+\.\d+/',
                'required_with:latitude',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'website' => [
                'nullable',
                'url',
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
