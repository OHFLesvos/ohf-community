<?php

namespace App\Http\Requests\People\Bank;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeposit extends FormRequest
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
            'project' => 'required|numeric|exists:projects,id',
            'value' => 'required|numeric|not_in:0',
            'date' => 'required|date',
        ];
    }
}
