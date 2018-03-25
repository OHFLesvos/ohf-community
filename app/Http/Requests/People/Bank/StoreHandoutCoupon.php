<?php

namespace App\Http\Requests\People\Bank;

use Illuminate\Foundation\Http\FormRequest;
use App\CouponType;
use App\Person;

class StoreHandoutCoupon extends FormRequest
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
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:' . CouponType::findOrFail($this->coupon_type_id)->daily_amount,
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $coupon = CouponType::findOrFail($this->coupon_type_id);
            $person = Person::findOrFail($this->person_id);
            if (!$person->eligibleForCoupon($coupon)) {
                $validator->errors()->add('coupon_type_id', __('people.person_not_eligible_for_this_coupon'));
            }
            $lastHandout = $person->canHandoutCoupon($coupon);
            if ($lastHandout != null) {
                $validator->errors()->add('coupon_type_id', $lastHandout['message']);
            }
        });
    }
}
