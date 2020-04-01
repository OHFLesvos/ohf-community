<?php

namespace App\Http\Requests\People;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonDateOfBirth extends FormRequest
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
            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::today()->toDateString(),
            ],
        ];
    }
}
