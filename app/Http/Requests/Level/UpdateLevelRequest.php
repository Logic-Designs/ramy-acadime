<?php

namespace App\Http\Requests\Level;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Country;

class UpdateLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Fetch all country IDs to ensure each one has a price
        $countryIds = Country::pluck('id')->toArray();

        return [
            'name_en' => 'string|max:255|unique:levels,name_en,' . $this->level->id,
            'name_ar' => 'string|max:255|unique:levels,name_ar,' . $this->level->id,
            'description_en' => 'string|nullable',
            'description_ar' => 'string|nullable',
            'min_age' => 'integer|min:0',
            'gender' => 'in:male,female,both',

            // Prices validation
            'prices' => ['array'],
            'prices.*.country_id' => [
                'required',
                Rule::in($countryIds),
            ],
            'prices.*.price' => 'required|numeric|min:0',
        ];
    }
}
