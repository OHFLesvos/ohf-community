<?php

namespace App\Http\Requests\People\Bank;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\People\Bank\ImportExportController;

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
            'format' => 'required|in:' . implode(',', array_keys(ImportExportController::$formats)),
        ];
    }
}
