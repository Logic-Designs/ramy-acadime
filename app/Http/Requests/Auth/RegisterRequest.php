<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'birthday' => 'required|date',
            'gender' => ['required', 'string', Rule::in(['male', 'female'])],
            'city_id' => 'required|integer|exists:cities,id',
        ];
    }
}
