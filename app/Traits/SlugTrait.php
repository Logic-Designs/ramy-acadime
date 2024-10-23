<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SlugTrait
{
    /**
     * Generate a unique slug for any model.
     *
     * @param string $name
     * @param string $column - The column where the slug will be stored (usually 'slug').
     * @return string
     */
    public static function generateUniqueSlug($name, $column = 'slug')
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Check if the slug already exists in the model's table
        while (static::where($column, $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Boot the SlugTrait for the model.
     * Automatically generates a slug before saving the model (both on create and update).
     *
     * @return void
     */
    protected static function bootSlugTrait()
    {
        // On creating a new model
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = self::generateUniqueSlug($model->name); // Adjust based on the attribute used for slug generation
            }
        });

        // On updating an existing model
        static::updating(function ($model) {
            // Only update the slug if the name has changed
            if ($model->isDirty('name')) {
                $model->slug = self::generateUniqueSlug($model->name);
            }
        });
    }
}
