<?php

namespace Modules\UserManagement\Http\Requests;

use App\Role;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'pwned',
            ],
            'roles' => [
                'array',
                Rule::in(Role::select('id')->get()->pluck('id')),
            ],
        ];
    }
}
