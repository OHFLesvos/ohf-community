<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LanguageName implements Rule
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
        $languageName = localized_language_names()
            ->map(fn ($l) => strtolower($l))
            ->flip()
            ->get(strtolower($value));
        return $languageName !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.language_name');
    }
}
