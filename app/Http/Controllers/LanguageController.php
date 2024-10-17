<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function getLanguage($locale)
    {
        // Path to language JSON files
        $langPath = resource_path('lang/' . $locale . '.json');

        // Check if the language file exists
        if (File::exists($langPath)) {
            // Return the contents of the language file as a JSON response
            return response()->json(json_decode(File::get($langPath)));
        }

        // If the language file doesn't exist, return a 404 error
        return response()->json(['error' => 'Language not found'], 404);
    }
}
