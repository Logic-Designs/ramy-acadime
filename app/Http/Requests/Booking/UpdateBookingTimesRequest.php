<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingTimesRequest extends FormRequest
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
            'times' => 'nullable|array',
            'times.*.booking_time_id' => [
                'required',
                'exists:booking_times,id',
                function ($attribute, $value, $fail) {
                    $bookingId = $this->route('booking')->id;
                    if (!\App\Models\BookingTime::where('id', $value)->where('booking_id', $bookingId)->exists()) {
                        $fail("The provided booking time ID does not belong to this booking.");
                    }
                },
            ],
            'times.*.date' => [
                'required_with:times',
                'date',
                'after:today',
                function ($attribute, $value, $fail) {
                    $dates = array_column($this->input('times', []), 'date');
                    if (count(array_unique($dates)) !== count($dates)) {
                        $fail("Dates must be unique across all times.");
                    }
                },
            ],
            'times.*.session_time_id' => [
                'required_with:times',
                'exists:session_times,id',
                function ($attribute, $value, $fail) {
                    $locationId = $this->input('location_id', $this->route('booking')->location_id);
                    if (!\App\Models\Location::find($locationId)->sessionTimes()->where('id', $value)->exists()) {
                        $fail("The selected session time is not available at the chosen location.");
                    }
                },
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'times.*.booking_time_id.exists' => 'The provided booking time ID does not exist.',
            'times.*.date.required_with' => 'Date is required when times are provided.',
            'times.*.session_time_id.exists' => 'The selected session time is not valid for this location.',
        ];
    }
}
