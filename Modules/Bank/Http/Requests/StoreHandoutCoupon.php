<?php

namespace Modules\Bank\Http\Requests;

use Modules\People\Entities\Person;

use Modules\Bank\Entities\CouponType;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

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
            'person_id' => [
                'required',
                'exists:persons,id',
            ],
            'coupon_type_id' => [
                'required',
                'exists:coupon_types,id',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:' . CouponType::findOrFail($this->coupon_type_id)->daily_amount,
            ],
            'code' => [
                'nullable',
                Rule::unique('coupon_handouts')->where(function ($query) {
                    return $query->where('date', Carbon::today());
                })
            ]
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
                $validator->errors()->add('coupon_type_id', __('people::people.person_not_eligible_for_this_coupon'));
            }
            $lastHandout = $person->canHandoutCoupon($coupon);
            if ($lastHandout != null) {
                $validator->errors()->add('coupon_type_id', $lastHandout);
            }
        });
    }
}
