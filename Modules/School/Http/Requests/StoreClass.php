<?php

namespace Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClass extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
            ],
            'teacher_name' => [
                'required',
            ],
            'room_name' => [
                'required',
            ],
            'capacity' => [
                'required',
                'numeric',
                'min:1',
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
