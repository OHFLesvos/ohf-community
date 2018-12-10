<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Isbn implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
        $isbn = new \Biblys\Isbn\Isbn($value);
        return $isbn->isValid();
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
