<?php

namespace App\Http\Requests\Booking;

use App\Models\BookingTime;
use App\Models\Level;
use App\Models\Location;
use App\Models\SessionTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'level_id' => [
                'required',
                'exists:levels,id',
                function ($attribute, $value, $fail) {
                    $level = Level::with('sessions')->find($value);
                    if (!$level || $level->sessions->count() === 0) {
                        $fail("The selected level has no sessions available.");
                    }
                },
            ],
            'location_id' => [
                'required',
                'exists:locations,id',
                function ($attribute, $value, $fail) {
                    $userCityId = Auth::user()->profile->city->id ?? null;
                    $location = Location::find($value);

                    if (!$location || $location->city_id !== $userCityId) {
                        $fail("The selected location is not available in your city.");
                    }
                },
            ],
            'times' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $level = Level::find($this->input('level_id'));
                    if ($level && count($value) !== $level->sessions->count()) {
                        $fail("The number of times provided must match the number of sessions for the selected level.");
                    }
                },
            ],
            'times.*.date' => [
                'required',
                'date',
                'after:today',
                'unique_booking_time',
                function ($attribute, $value, $fail) {
                    $dates = array_column($this->input('times', []), 'date');
                    if (count(array_unique($dates)) !== count($dates)) {
                        $fail("Dates must be unique across all times.");
                    }
                },
            ],
            'times.*.session_time_id' => [
                'required',
                'exists:session_times,id',
                'exists_in_location:' . $this->location_id,
                'matches_day_of_week'
            ],
        ];
    }

     /**
     * Define custom validation messages.
     */
    public function messages()
    {
        return [
            'times.*.date.unique_booking_time' => 'The session time on the given date is already booked.',
            'times.*.session_time_id.exists_in_location' => 'The selected session time is not available at the chosen location.',
            'times.*.session_time_id.matches_day_of_week' => 'The selected session time does not match the day of the week for the given date.',
        ];
    }



     /**
     * Customize the validator instance.
     */
    protected function prepareForValidation()
    {
        // Add custom validation rule
        $this->merge([
            'times' => $this->times, // Ensure it's passed correctly
        ]);
    }

    /**
     * Register custom validation rules.
     */
    public function withValidator($validator)
    {
        $validator->addExtension('unique_booking_time', function ($attribute, $value, $parameters, $validator) {
            preg_match('/times\.(\d+)\.date/', $attribute, $matches);
            $index = $matches[1] ?? null;

            if ($index === null) {
                return false;
            }
            $sessionTimeId = request("times.$index.session_time_id");
            return !BookingTime::where('session_time_id', $sessionTimeId)
                ->where('date', $value)
                ->exists();
        });

        $validator->addExtension('exists_in_location', function ($attribute, $value, $parameters, $validator) {
            $locationId = $parameters[0];
            return Location::find($locationId)->sessionTimes()->where('id', $value)->exists();
        });

        // Add a new custom rule for validating the session_time_id and day of the week
        $validator->addExtension('matches_day_of_week', function ($attribute, $value, $parameters, $validator) {
            preg_match('/times\.(\d+)\.session_time_id/', $attribute, $matches);
            $index = $matches[1] ?? null;

            if ($index === null) {
                return false;
            }

            $date = request("times.$index.date");
            $dayOfWeek = \Carbon\Carbon::parse($date)->format('l');

            // Check if the day of the week matches the session time's day
            $sessionTime = SessionTime::find($value);

            return $sessionTime && $sessionTime->day_of_week === $dayOfWeek;
        });
    }



}
