<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class StoreUserRequest extends FormRequest
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
        $roles = Role::pluck('name')->toArray();

        $rules = [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image',
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'role' => ['nullable', 'string', Rule::in($roles)],
        ];

        if ($this->input('role') === 'user' || ! $this->input('role')) {
            $rules = array_merge($rules, [
                'address' => 'required|string|max:255',
                'bio' => 'nullable|string|max:500',
                'birthday' => 'required|date',
                'gender' => ['required', 'string', Rule::in(['male', 'female'])],
                'city_id' => 'required|integer|exists:countries,id',
            ]);
        }

        return $rules;
    }
}
