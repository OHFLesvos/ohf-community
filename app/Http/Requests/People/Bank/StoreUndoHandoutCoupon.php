<?php

namespace App\Http\Requests\People\Bank;

use Illuminate\Foundation\Http\FormRequest;

class StoreUndoHandoutCoupon extends FormRequest
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
            'person_id' => 'required|exists:persons,id',
            'coupon_type_id' => 'required|exists:coupon_types,id',
        ];
    }
}
