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
            'address' => 'required',
            'category' => 'required',
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
            ]
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
