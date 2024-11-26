<?php

namespace App\Rules;

use App\Models\Location;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class LocationInUserCity implements ValidationRule
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
        // Get the authenticated user's city ID (null if no user or profile/city)
        $userCityId = Auth::user()->profile->city->id ?? null;

        // Check if user city is available
        if (!$userCityId) {
            $fail("User city not found.");
            return;
        }

        // Find the location by the provided value
        $location = Location::find($value);

        // If location doesn't exist or its city doesn't match user's city
        if (!$location || $location->city_id !== $userCityId) {
            $fail("The selected location is not available in your city.");
        }
    }
}
