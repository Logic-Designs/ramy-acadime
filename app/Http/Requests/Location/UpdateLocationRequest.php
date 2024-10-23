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
            'name' => 'string|max:255|unique:locations,name,' . $this->location->id,
            'country_id' => 'exists:countries,id',
            'city' => 'string',
            'address' => 'string',
            'map' => 'url',
        ];
    }
}
