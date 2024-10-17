<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Implement authorization as needed
    }

    public function rules()
    {
        return [
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name', // Assuming permission names are stored in the 'name' column
        ];
    }
}
