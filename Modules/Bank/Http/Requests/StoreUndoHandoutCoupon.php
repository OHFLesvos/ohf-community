<?php

namespace Modules\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

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
                ->whereDate('date', Carbon::today())
                ->first();
            if ($handout != null) {
                if ($handout->created_at->diffInSeconds(Carbon::now()) > 60) {
                    $validator->errors()->add('coupon_type_id', 'Time is up');
                }
            } else {
                $validator->errors()->add('coupon_type_id', 'Handout not found!');
                // TODO __('people::people.person_not_eligible_for_this_coupon')
            }
        });
    }
}
