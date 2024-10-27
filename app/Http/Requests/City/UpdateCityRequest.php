<?php

namespace App\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust as needed for authorization
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $cityId = $this->route('city'); // Get the city ID from the route

        return [
            'name_en' => 'sometimes|required|string|max:255|unique:cities,name_en,' . $this->city->id,
            'name_ar' => 'sometimes|required|string|max:255|unique:cities,name_ar,' . $this->city->id,
            'country_id' => 'sometimes|required|exists:countries,id',
        ];
    }
}
