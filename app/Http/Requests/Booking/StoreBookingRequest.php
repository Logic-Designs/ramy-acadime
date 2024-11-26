<?php

namespace App\Http\Requests\Booking;

use App\Models\Level;
use App\Models\Location;
use App\Rules\CheckSessionAvailability;
use App\Rules\LevelExistsWithSessions;
use App\Rules\LocationInUserCity;
use App\Rules\TimesMatchLevelSessions;
use App\Rules\UniqueDatesInTimes;
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
                new LevelExistsWithSessions
            ],
            'location_id' => [
                'required',
                'exists:locations,id',
                new LocationInUserCity,
            ],
            'times' => [
                'required',
                'array',
                new TimesMatchLevelSessions
            ],
            'times.*.date' => [
                'required',
                'date',
                'after:today',
                new UniqueDatesInTimes
            ],
            'times.*.session_time_id' => [
                'required',
                'exists:session_times,id',
                new CheckSessionAvailability(Auth::id()),
            ],
        ];
    }

}
