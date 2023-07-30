<?php

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfile extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                Rule::requiredIf(Auth::user()->provider_name === null),
                Rule::excludeIf(Auth::user()->provider_name !== null),
                'string',
                'max:191',
            ],
            'email' => [
                Rule::requiredIf(Auth::user()->provider_name === null),
                Rule::excludeIf(Auth::user()->provider_name !== null),
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
