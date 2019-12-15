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
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:' . $this->couponType->daily_amount,
            ],
            'code' => [
                'nullable',
                Rule::unique('coupon_handouts')->where(function ($query) {
                    $expiry = $this->couponType->code_expiry_days;
                    if ($expiry != null) {
                        return $query->whereDate('date', '>=', Carbon::today()->subDays($expiry - 1));
                    }
                    return $query;
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
            $coupon = $this->couponType;
            $person = $this->person;
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
