<?php

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreUserProfile extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:191',
            ],
            'email' => [
                Rule::requiredIf(empty(Auth::user()->provider_name)),
                'string',
                'email',
                'max:191',
                Rule::unique('users')->ignore(Auth::user()->id),
            ],
            'locale' => [
                Rule::in(array_keys(language()->allowed())),
            ],
        ];
    }
}
