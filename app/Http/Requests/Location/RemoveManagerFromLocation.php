<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class RemoveManagerFromLocation extends FormRequest
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
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $locationId = $this->route('location')->id;
                    $exists = DB::table('location_user')
                        ->where('location_id', $locationId)
                        ->where('user_id', $value)
                        ->exists();

                    if (!$exists) {
                        $fail('The selected user is not assigned to this location.');
                    }
                },
            ],
        ];
    }
}
