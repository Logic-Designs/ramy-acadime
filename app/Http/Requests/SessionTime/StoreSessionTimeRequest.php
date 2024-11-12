<?php

namespace App\Http\Requests\SessionTime;

use App\Models\SessionTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreSessionTimeRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day_of_week' => 'required|in:' . implode(',',SessionTime::DAYS_OF_WEEK),
            'location_id' => [
                'required',
                'exists:locations,id',
                function ($attribute, $value, $fail) {
                    $dayOfWeek = $this->input('day_of_week');
                    $startTime = $this->input('start_time');
                    $endTime = $this->input('end_time');

                    // Check for overlapping session times
                    $overlap = SessionTime::where('location_id', $value)
                        ->where('day_of_week', $dayOfWeek)
                        ->where(function($query) use ($startTime, $endTime) {
                            $query->where(function ($query) use ($startTime) {
                                        $query->where('start_time', '<=', $startTime)
                                              ->where('end_time', '>', $startTime);
                                    })
                                  ->orWhere(function ($query) use ($endTime) {
                                        $query->where('start_time', '<', $endTime)
                                              ->where('end_time', '>=', $endTime);
                                    })
                                  ->orWhere(function ($query) use ($startTime, $endTime) {
                                        $query->where('start_time', '>=', $startTime)
                                              ->where('end_time', '<=', $endTime);
                                    });
                        })
                        ->exists();

                    if ($overlap) {
                        $fail('The session time overlaps with an existing session time for this location and day.');
                    }
                },


            ],
        ];
    }
}
