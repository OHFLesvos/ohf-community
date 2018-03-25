<?php

namespace App\Http\Requests\People\Bank;

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
            'person_id' => 'required|numeric|exists:persons,id',
            'card_no' => 'required',
        ];
    }
}
