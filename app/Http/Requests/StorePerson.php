<?php

namespace App\Http\Requests;

use App\Util\CountriesExtended;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePerson extends FormRequest
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
            'name' => 'required|max:255',
            'family_name' => 'required|max:255',
            'gender' => 'required|in:m,f',
            'nationality' => [
				'nullable',
				'max:255',
				Rule::in(CountriesExtended::getList('en'))
			],
            'medical_no' => 'max:255',
            'registration_no' => 'max:255',
            'section_card_no' => 'max:255',
            'temp_no' => 'max:255',
            'languages' => 'max:255',
            'skills' => 'max:255',
            'remarks' => 'max:255',
            'date_of_birth' => 'nullable|date',
        ];
    }
}
