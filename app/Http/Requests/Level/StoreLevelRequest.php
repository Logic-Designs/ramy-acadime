<?php

namespace App\Http\Requests\Level;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $countryIds = Country::pluck('id')->toArray();

        return [
            'name_en' => 'required|string|max:255|unique:levels,name_en',
            'name_ar' => 'required|string|max:255|unique:levels,name_ar',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'min_age' => 'required|integer|min:0',
            'gender' => 'required|in:male,female,both',

            // Prices validation
            'prices' => ['required', 'array', 'size:' . count($countryIds)],
            'prices.*.country_id' => [
                'required',
                Rule::in($countryIds),
            ],
            'prices.*.price' => 'required|numeric|min:0',
        ];
    }
}
