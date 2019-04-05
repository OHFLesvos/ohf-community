<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadSpreadsheet extends FormRequest
{
    private const MIMES = [
        'xlsx',
        'xls',
        'ods',
        'csv',
        'tsv',
        'slk',
        'xml',
        'gnumeric',
        'html',
    ];

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
            'file' => [
                'required',
                'file',
                'mimes:' . implode(',', self::MIMES),
            ]
        ];
    }
}
