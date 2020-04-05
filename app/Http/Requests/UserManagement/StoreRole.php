<?php

namespace App\Http\Requests\UserManagement;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRole extends FormRequest
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
                isset($this->role) ? Rule::unique('roles')->ignore($this->role->id) : Rule::unique('roles'),
            ],
            'users' => [
                'array',
                Rule::in(User::select('id')->get()->pluck('id')),
            ],
            'role_admins' => [
                'array',
                Rule::in(User::select('id')->get()->pluck('id')),
            ],
            'permissions' => [
                'array',
                Rule::in(array_keys(config('auth.permissions'))),
            ],
        ];
    }
}
