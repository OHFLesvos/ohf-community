<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\User;

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
                'max:255',
                isset($this->role) ? Rule::unique('roles')->ignore($this->role->id) : Rule::unique('roles')
            ],
            'users' => 'array|in:' . User::select('id')->get()->pluck('id')->implode(','),
            'permissions' => 'array|in:' . implode(',', array_keys(Config::get('auth.permissions'))),
        ];
    }
}
