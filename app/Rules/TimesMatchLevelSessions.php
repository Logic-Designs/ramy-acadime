<?php

namespace App\Rules;

use App\Models\Level;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TimesMatchLevelSessions implements ValidationRule
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
        // Retrieve the level_id from the input
        $levelId = request()->input('level_id');

        // Find the level by ID
        $level = Level::find($levelId);

        // If the level exists and the number of times doesn't match the number of sessions
        if ($level && count($value) !== $level->sessions->count()) {
            $fail("The number of times provided must match the number of sessions for the selected level.");
        }
    }
}
