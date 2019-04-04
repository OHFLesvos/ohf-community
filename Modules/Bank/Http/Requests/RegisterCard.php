<?php

namespace Modules\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCard extends FormRequest
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
            'card_no' => [
                'required',
                'alpha_num',
                'unique:persons,card_no',
            ],
        ];
    }
}
