<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
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
            // Ensure both English and Arabic names are validated and are unique
            'name_en' => 'required|string|max:255|unique:locations,name_en',
            'name_ar' => 'required|string|max:255|unique:locations,name_ar',

            // Ensure country exists in the countries table
            'city_id' => 'required|exists:cities,id',


            // Validate addresses (optional, as per your requirements)
            'address_en' => 'nullable|string|max:255',
            'address_ar' => 'nullable|string|max:255',

            // Ensure the map field is a valid URL
            'map' => 'nullable|url',
        ];
    }
}
