<?php

namespace App\Http\Requests\CommunityVolunteers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResponsibility extends FormRequest
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
                isset($this->responsibility)
                    ? Rule::unique('helpers_responsibilities')->ignore($this->responsibility->id)
                    : Rule::unique('helpers_responsibilities'),
            ],
            'capacity' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'available' => [
                'boolean',
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
