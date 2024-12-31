<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')->id; // Get the ID of the user being updated

        $rules = [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'phone' => [
                'sometimes',
                'required',
                'string',
                'max:15',
                Rule::unique('users')->ignore($userId),
            ],
            'avatar' => 'nullable|image',
            'password' => 'nullable|string|min:8|confirmed',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'role' => ['nullable', 'string', Rule::in($roles)],
        ];

        if (User::find($userId)->hasRole('user')) {
            $rules = array_merge($rules, [
                'address' => 'nullable|string|max:255',
                'bio' => 'nullable|string|max:500',
                'birthday' => 'nullable|date',
                'gender' => ['nullable', 'string', Rule::in(['male', 'female'])],
                'city_id' => 'nullable|integer|exists:countries,id',
            ]);
        }

        return $rules;
    }
}
