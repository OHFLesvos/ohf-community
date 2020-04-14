<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Languages;

class LanguageCode implements Rule
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
        return Languages::lookup([$value])->isNotEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.language_code');
    }
}
