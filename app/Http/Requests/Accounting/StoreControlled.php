<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreControlled extends FormRequest
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
            //
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
            if ($this->transaction->controlled_at !== null) {
                $validator->errors()->add('controlled_at', __('app.already_controlled'));
            }
            $audit = $this->transaction->audits()->first();
            if (isset($audit) && $audit->getMetadata()['user_id'] == Auth::user()->id) {
                $validator->errors()->add('controlled_at', __('app.controlling_by_user_who_created_not_allowed'));
            }
        });
    }
}
