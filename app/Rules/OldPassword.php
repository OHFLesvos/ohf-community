<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class OldPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @param $currentPasswordHash the hash of the current password
     */
    public function __construct(private $currentPasswordHash)
    {
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
        return Hash::check($value, $this->currentPasswordHash);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.old_password');
    }
}
