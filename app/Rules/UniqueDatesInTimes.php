<?php

namespace App\Rules;

use App\Models\BookingTime;
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
        // Get all times from the request
        $times = request()->input('times', []);
        if (is_array($times) && !empty($times)) {
            // Store case: Validate that all dates in the array are unique
            $dates = array_column($times, 'date');
            if (count(array_unique($dates)) !== count($dates)) {
                $fail("Dates must be unique across all times.");
            }
        } else {
            // Update case: Validate that the date is unique (example: check against database)
            $otherDates = $this->getOtherDatesFromDatabase($attribute, $value);
            if (in_array($value, $otherDates)) {
                $fail("The date '$value' is already taken.");
            }
        }
    }

    /**
     * Get other dates from the database to check uniqueness for updates.
     *
     * @param string $attribute
     * @param mixed $value
     * @return array
     */
    protected function getOtherDatesFromDatabase(string $attribute, mixed $value): array
    {
        $bookingTime = request()->route('bookingTime');
        return BookingTime::where('id', '!=', $bookingTime->id)
            ->where('booking_id', $bookingTime->booking_id)->pluck('date')->toArray();
    }
}
