<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotSvg implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $extension = strtolower($value->getClientOriginalExtension());
        return $extension !== 'svg';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'SVG images are not supported.';
    }
}
