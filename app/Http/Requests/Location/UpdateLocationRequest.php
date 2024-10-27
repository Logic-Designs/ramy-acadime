<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            // Validate English and Arabic names only if provided and ensure uniqueness
            'name_en' => 'string|max:255|unique:locations,name_en,' . $this->location->id,
            'name_ar' => 'string|max:255|unique:locations,name_ar,' . $this->location->id,

            'city_id' => 'exists:cities,id',

            // Validate addresses (optional) if provided
            'address_en' => 'nullable|string|max:255',
            'address_ar' => 'nullable|string|max:255',

            // Ensure the map is a valid URL if provided
            'map' => 'nullable|url',
        ];
    }
}
