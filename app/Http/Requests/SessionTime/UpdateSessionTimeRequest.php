<?php

namespace App\Http\Requests\SessionTime;

use App\Models\SessionTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateSessionTimeRequest extends FormRequest
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
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            // 'day_of_week' => 'sometimes|in:' . implode(',', SessionTime::DAYS_OF_WEEK),
            // 'location_id' excluded to prevent updates
        ];
    }

    /**
     * Configure the validator instance to check for overlapping times.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sessionTime = $this->route('session_time');


            $sessionTimeId = $sessionTime->id;
            $locationId = $sessionTime->location_id;
            $dayOfWeek = $sessionTime->day_of_week;
            $startTime = $this->input('start_time') ?? $sessionTime->start_time;
            $endTime = $this->input('end_time') ?? $sessionTime->end_time;

            if ($dayOfWeek && $startTime && $endTime) {
                $overlap = SessionTime::where('location_id', $locationId)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('id', '!=', $sessionTimeId)
                    ->where(function ($query) use ($startTime, $endTime) {
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
                    $validator->errors()->add('time', 'The session time overlaps with an existing session time for this location and day.');
                }
            }
        });
    }
}
