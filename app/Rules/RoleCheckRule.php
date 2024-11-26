<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;

class RoleCheckRule implements ValidationRule
{
    /**
     * The roles to check.
     */
    protected array $roles;

    /**
     * Create a new rule instance.
     *
     * @param  array|string  $roles One or more roles to validate against
     */
    public function __construct(array|string $roles)
    {
        $this->roles = (array) $roles; // Ensure roles are an array
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Retrieve the user by the given ID
        $user = User::find($value);
        if (!$user || !$user->hasAnyRole($this->roles)) {
            $rolesList = implode(', ', $this->roles);
            $fail("The selected $attribute must have one of the following roles: $rolesList.");
        }
    }
}
