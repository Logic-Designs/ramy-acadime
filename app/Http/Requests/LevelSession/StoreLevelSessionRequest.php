<?php

namespace App\Http\Requests\LevelSession;

use Illuminate\Foundation\Http\FormRequest;

class StoreLevelSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
        ];
    }
}
