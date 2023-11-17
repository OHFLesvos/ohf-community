<?php

namespace App\Http\Requests\CommunityVolunteers;

use Countries;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommunityVolunteer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'max:191',
            ],
            'family_name' => [
                'required',
                'max:191',
            ],
            'nickname' => [
                'nullable',
                'max:191',
            ],
            'nationality' => [
                'nullable',
                'max:191',
                Rule::in(Countries::getList('en')),
            ],
            'gender' => [
                'required',
                Rule::in(['m', 'f']),
            ],
            'date_of_birth' => [
                'required',
                'date',
            ],
            'police_no' => [
                'nullable',
            ],
            'email' => [
                'nullable',
                'email',
            ],
        ];
    }
}
