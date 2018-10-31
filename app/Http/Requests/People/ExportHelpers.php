<?php

namespace App\Http\Requests\People;

use Illuminate\Foundation\Http\FormRequest;

class ExportHelpers extends FormRequest
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
            'format' => 'required|in:xlsx,csv,tsv',
            'scope' => 'required',
        ];
    }
}
