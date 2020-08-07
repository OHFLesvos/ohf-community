<?php

namespace App\Http\Requests\CommunityVolunteers;

use Illuminate\Foundation\Http\FormRequest;

class ImportCommunityVolunteers extends FormRequest
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
            'file' => 'required|file',
            'map' => 'array',
            'map.*.from' => 'required_with:map.*.to',
        ];
    }
}
