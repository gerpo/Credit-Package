<?php

namespace Gerpo\DmsCredits\Rules;

use Gerpo\DmsCredits\Models\Code;
use Illuminate\Contracts\Validation\Rule;

class CodeExists implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Code::active()->where('code', strtoupper($value))->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
