<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPhoneNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * The regex checks for a valid Bangladeshi mobile number format.
     * It allows for an optional '+88' prefix, followed by '01' 
     * and then 9 digits from the valid operator ranges.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Regex for Bangladeshi mobile numbers (e.g., 017xxxxxxxx, +88018xxxxxxxx)
        return preg_match('/^(?:\+?88)?01[3-9]\d{8}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The phone number is not a valid Bangladeshi mobile number.');
    }
}