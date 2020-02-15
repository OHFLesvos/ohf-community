<?php

namespace App\Http\Requests\People;

use App\Models\People\Person;
use App\Models\People\RevokedCard;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePerson extends FormRequest
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
            'name' => [
                'required',
                'max:191',
            ],
            'family_name' => [
                'required',
                'max:191',
            ],
            'gender' => [
                'required',
                'in:m,f',
            ],
            'nationality' => [
				'nullable',
				'max:191',
				Rule::in(\Countries::getList('en')),
			],
            'languages' => [
                'max:191',
            ],
            'remarks' => [
                'max:191',
            ],
            'date_of_birth' => [
                'nullable',
                'date',
            ],
            'card_no' => [
                'nullable',
                'alpha_num'
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
            if (!empty($this->card_no)) {
                $card_no = $this->card_no;

                // Check for revoked card number
                $revoked = RevokedCard::where('card_no', $card_no)->first();
                if ($revoked != null) {
                    $validator->errors()->add('card_no', __('people.card_revoked', [ 'card_no' => substr($card_no, 0, 7), 'date' => $revoked->created_at ]));
                }

                // Check for used card number
                if (Person::where('card_no', $card_no)->count() > 0) {
                    $validator->errors()->add('card_no', __('people.card_already_in_use', [ 'card_no' => substr($card_no, 0, 7) ]));
                }
            }
        });
    }
}
