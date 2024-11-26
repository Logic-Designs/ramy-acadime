<?php

namespace App\Rules;

use App\Models\BookingTime;
use App\Models\SessionTime;
use App\Models\Location;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class CheckSessionAvailability implements ValidationRule
{
    protected $userId;
    protected $cityId;

    public function __construct($userId)
    {
        // Initialize userId and cityId for the validation rule
        $this->userId = $userId;
        $this->cityId = User::findOrFail($userId)->profile->city->id ?? null;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        preg_match('/times\.(\d+)\.session_time_id/', $attribute, $matches);
        $index = $matches[1] ?? null;

        if ($index === null) {
            return;
        }

        $sessionTimeId = request("times.$index.session_time_id");
        $date = request("times.$index.date");
        $sessionTime = SessionTime::findOrFail($sessionTimeId);

        $location = Location::findOrFail($sessionTime->location_id);
        if ($location->city_id != $this->cityId) {
            $fail("The selected location is not available in your city.");
            return;
        }

        $dayOfWeek = Carbon::parse($date)->format('l');
        if ($sessionTime->day_of_week !== $dayOfWeek) {
            $fail("The selected session time does not match the day of the week for the given date.");
            return;
        }

        $isAvailable = !BookingTime::where('session_time_id', $sessionTimeId)
            ->where('date', $date)
            ->exists();

        if (!$isAvailable) {
            $fail("The session time on the given date is already booked.");
        }
    }
}
