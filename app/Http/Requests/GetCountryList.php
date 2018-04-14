<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCountryList extends FormRequest
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
            'lang' => 'nullable|alpha',
            'query' => 'nullable|string',
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
            if (isset($this->lang)) {
                try {
                    \Countries::getList($this->lang);
                } catch (\RuntimeException $e) {
                    $validator->errors()->add('lang', 'Unable to load country list for language ' . $this->lang);
                }
            }
        });
    }
}
