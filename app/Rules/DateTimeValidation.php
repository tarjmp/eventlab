<?php

namespace App\Rules;

use App\Tools\Date;
use Illuminate\Contracts\Validation\Rule;

class DateTimeValidation implements Rule
{
    private $sDate = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($sDate)
    {
        $this->sDate = $sDate;
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
        return !empty($value) && (Date::parseFromInput($this->sDate, $value) !== null);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.required');
    }
}
