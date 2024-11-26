<?php

namespace App\Rules;

use App\Models\Level;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LevelExistsWithSessions implements ValidationRule
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
        // Find the level by ID
        $level = Level::with('sessions')->find($value);

        // Check if the level exists and has sessions
        if (!$level || $level->sessions->count() === 0) {
            $fail("The selected level has no sessions available.");
        }
    }
}
