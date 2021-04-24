<?php

namespace App\Rules;

use Countries;
use Illuminate\Contracts\Validation\Rule;

class CountryCode implements Rule
{
    public function validate($attribute, $value, $params)
    {
        return $this->passes($attribute, $value);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return collect(Countries::getList())->keys()->contains($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.country_code');
    }
}
