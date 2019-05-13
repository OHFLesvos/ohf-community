<?php

namespace Modules\UserManagement\Http\Requests;

use App\Rules\OldPassword;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewUserPassword extends FormRequest
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
            'old_password' => [
                'required',
                new OldPassword(Auth::user()->password)
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'pwned',
                'confirmed',
            ],
        ];
    }
}
