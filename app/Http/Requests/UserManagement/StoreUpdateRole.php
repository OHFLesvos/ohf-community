<?php

namespace App\Http\Requests\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreUpdateRole extends FormRequest
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
                Rule::unique('roles')
                    ->when(isset($this->role), fn (Unique $rule) => $rule->ignore($this->role->id)),
            ],
            'users' => [
                'array',
                Rule::in(User::pluck('id')),
            ],
            'role_admins' => [
                'array',
                Rule::in(User::pluck('id')),
            ],
            'permissions' => [
                'array',
                Rule::in(array_keys(config('permissions.keys'))),
            ],
        ];
    }
}
