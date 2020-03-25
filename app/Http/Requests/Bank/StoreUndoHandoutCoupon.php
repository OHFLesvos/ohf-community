<?php

namespace App\Http\Requests\Bank;

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
            $handout = $person->couponHandouts()
                ->where('coupon_type_id', $coupon->id)
                ->orderBy('date', 'desc')
                ->first();
            if ($handout != null) {
                if (! $handout->isReturningPossible) {
                    $validator->errors()->add('coupon_type_id', __('coupons.only_allowed_within_n_seconds_after_handout', ['seconds' => $handout::returningPossibleGracePeriod()]));
                }
            } else {
                $validator->errors()->add('coupon_type_id', __('app.not_found'));
            }
        });
    }
}
