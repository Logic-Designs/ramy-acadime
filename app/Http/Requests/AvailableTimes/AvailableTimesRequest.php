<?php

namespace App\Http\Requests\AvailableTimes;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AvailableTimesRequest extends FormRequest
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
    // In AvailableTimesRequest.php
    public function rules()
    {
        return [
            'user_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    /** @var User $user  */
                    $user = User::find($value);
                    if ($user && $user->hasRole('user')) {
                        $fail("Can't get avilable time for this user");
                    }
                },
            ],
        ];
    }

}
