<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SlugTrait
{
    /**
     * Generate a unique slug for any model.
     *
     * @param string $name
     * @param string $modelClass - The model class where the slug should be unique.
     * @return string
     */
    public static function generateUniqueSlug($name, $modelClass)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Check if the slug already exists in the specified model
        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
