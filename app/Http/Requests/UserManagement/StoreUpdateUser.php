<?php

namespace App\Http\Requests\UserManagement;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class StoreUpdateUser extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                Rule::requiredIf($this->user?->provider_name === null),
                Rule::excludeIf($this->user?->provider_name !== null),
                'string',
                'max:191',
            ],
            'email' => [
                Rule::requiredIf($this->user?->provider_name === null),
                Rule::excludeIf($this->user?->provider_name !== null),
                'string',
                'email',
                'max:191',
                Rule::unique('users')
                    ->when(isset($this->user), fn (Unique $rule) => $rule->ignore($this->user->id)),
            ],
            'password' => [
                $this->user === null ? 'required' : 'nullable',
                Rule::excludeIf($this->user?->provider_name !== null),
                Password::defaults(),
            ],
            'roles' => [
                'array',
                Rule::in(Role::pluck('id')),
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
