<?php

namespace App\Http\Requests\UserManagement;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
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
                Rule::requiredIf(empty($this->user->provider_name)),
                'string',
                'email',
                'max:191',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'pwned',
            ],
            'roles' => [
                'array',
                Rule::in(Role::select('id')->get()->pluck('id')),
            ],
            'locale' => [
                'nullable',
                Rule::in(config('language.allowed')),
            ],
            'is_super_admin' => [
                'boolean',
            ],
        ];
    }
}
