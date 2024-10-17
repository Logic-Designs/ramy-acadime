<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class LanguageManagementController extends Controller
{
    // Show the translation keys and values
    public function index()
    {
        // Load both language files
        $languages = [
            'en' => json_decode(File::get(resource_path('lang/en.json')), true),
            'ar' => json_decode(File::get(resource_path('lang/ar.json')), true),
        ];

        // Pass the data to the view
        return view('languages.index', compact('languages'));
    }

    // Update a key's value
    public function update(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'locale' => 'required|in:en,ar',
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        // Get the language file
        $filePath = resource_path('lang/' . $request->locale . '.json');

        // Load existing translations
        $translations = json_decode(File::get($filePath), true);

        // Update the key with the new value
        $translations[$request->key] = $request->value;

        // Save the updated translations back to the file
        File::put($filePath, json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Translation updated successfully']);
    }

    // Add a new key-value pair
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'locale' => 'required|in:en,ar',
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        // Get the language file
        $filePath = resource_path('lang/' . $request->locale . '.json');

        // Load existing translations
        $translations = json_decode(File::get($filePath), true);

        // Add new key-value pair
        $translations[$request->key] = $request->value;

        // Save the updated translations back to the file
        File::put($filePath, json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Translation added successfully']);
    }

    // Delete a translation key
    public function destroy(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'locale' => 'required|in:en,ar',
            'key' => 'required|string',
        ]);

        // Get the language file
        $filePath = resource_path('lang/' . $request->locale . '.json');

        // Load existing translations
        $translations = json_decode(File::get($filePath), true);

        // Remove the key
        unset($translations[$request->key]);

        // Save the updated translations back to the file
        File::put($filePath, json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Translation deleted successfully']);
    }
}
