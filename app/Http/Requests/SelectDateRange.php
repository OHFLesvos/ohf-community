<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

class SelectDateRange extends FormRequest
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
            'from' => [
                'required',
                'date',
            ],
            'to' => [
                'required',
                'date',
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
            $from = new Carbon($this->from);
            $to = new Carbon($this->to);
            if ($from->gte($to)) {
                $validator->errors()->add('from', '"from" date must be before "to" date');
            }
        });
    }
}

