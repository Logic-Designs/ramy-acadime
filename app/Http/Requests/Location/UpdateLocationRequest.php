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
            'name_en' => 'nullable|string|max:255|unique:locations,name_en,' . $this->location->id,
            'name_ar' => 'nullable|string|max:255|unique:locations,name_ar,' . $this->location->id,

            // Ensure the country exists in the countries table if provided
            'country_id' => 'nullable|exists:countries,id',

            // Validate cities in both English and Arabic if provided
            'city_en' => 'nullable|string|max:255',
            'city_ar' => 'nullable|string|max:255',

            // Validate addresses (optional) if provided
            'address_en' => 'nullable|string|max:255',
            'address_ar' => 'nullable|string|max:255',

            // Ensure the map is a valid URL if provided
            'map' => 'nullable|url',
        ];
    }
}
