<?php

namespace App\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCountryRequest extends FormRequest
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
            'name_en' => 'string|max:255|unique:countries,name_en,' . $this->country->id,
            'name_ar' => 'string|max:255|unique:countries,name_ar,' . $this->country->id,
            'currency_code_en' => 'string|size:3',
            'currency_code_ar' => 'string|max:255',
        ];
    }
}
