<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueDatesInTimes implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Get all dates from the 'times' input field
        $dates = array_column(request()->input('times', []), 'date');

        // Check if the current date value already exists in the array
        if (count(array_unique($dates)) !== count($dates)) {
            $fail("Dates must be unique across all times.");
        }
    }
}
