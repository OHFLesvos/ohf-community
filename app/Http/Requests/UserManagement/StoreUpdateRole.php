<?php

namespace App\Http\Requests\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreUpdateRole extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
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
