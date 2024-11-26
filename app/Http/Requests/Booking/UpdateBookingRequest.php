<?php

namespace App\Http\Requests\Booking;

use App\Rules\LevelExistsWithSessions;
use App\Rules\LocationInUserCity;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'status' => 'in:reserved,confirmed',
            'payment_status' => 'in:paid,unpaid',
            'level_id' => ['exists:levels,id', new LevelExistsWithSessions],
            // 'location_id' => ['exists:locations,id', new LocationInUserCity],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'status.in' => 'The status must be either reserved or confirmed.',
            'payment_status.in' => 'The payment status must be either paid or unpaid.',
            'level_id.exists' => 'The selected level is invalid.',
        ];
    }
}
