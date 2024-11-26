<?php

namespace App\Http\Requests\Booking;

use App\Models\BookingTime;
use App\Rules\CheckSessionAvailability;
use App\Rules\RoleCheckRule;
use App\Rules\UniqueDatesInTimes;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingTimeRequest extends FormRequest
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
        $bookingTime = $this->route('bookingTime');

        if (!$bookingTime) {
            abort(404, 'Booking time not found');
        }

        $userId = $bookingTime->booking->user_id ?? null;

        return [
            'date' => [
                'date',
                'after:today',
                new UniqueDatesInTimes
            ],
            'session_time_id' => [
                'exists:session_times,id',
                new CheckSessionAvailability($userId),
            ],
            'coach_id' => [
                'nullable',
                'exists:users,id',
                new RoleCheckRule(['coach']),
            ],
            'status' => [
                'nullable',
                'in:' . implode(',', \App\Models\BookingTime::STATUS), // Validate against allowed statuses
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages()
    {
        return [
            'date.required' => 'The date is required.',
            'date.after' => 'The date must be a future date.',
            'session_time_id.required' => 'The session time ID is required.',
            'status.in' => 'The status must be one of the following: taken, not_taken, no_show.',
        ];
    }


    public function withValidator($validator)
    {
        $validator->sometimes('session_time_id', 'required', function ($input) {
            return !empty($input->date);
        });

        $validator->sometimes('date', 'required', function ($input) {
            return !empty($input->session_time_id);
        });
    }
}
