<?php

namespace App\Rules\Library;

use Illuminate\Contracts\Validation\Rule;
use Nicebooks\Isbn\IsbnTools;

class Isbn implements Rule
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
        $tools = new IsbnTools();
        return $tools->isValidIsbn($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('library.invalid_isbn');
    }
}
