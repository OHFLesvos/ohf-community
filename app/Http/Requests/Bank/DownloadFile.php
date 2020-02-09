<?php

namespace App\Http\Requests\Bank;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class DownloadFile extends FormRequest
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
            'format' => [
                'required',
                Rule::in(array_keys(Config::get('bank.export_formats'))),
            ],
        ];
    }
}
