<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SlugTrait
{
    /**
     * Generate a unique slug for any model.
     *
     * @param string $value - The value to generate the slug from.
     * @param string $column - The column where the slug will be stored (usually 'slug').
     * @return string
     */
    public static function generateUniqueSlug($value, $column = 'slug')
    {
        $slug = Str::slug($value);
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
            $sourceColumn = $model->slugSource ?? 'name';  // Default to 'name' if slugSource is not set
            if (empty($model->slug)) {
                $model->slug = self::generateUniqueSlug($model->$sourceColumn);
            }
        });

        // On updating an existing model
        static::updating(function ($model) {
            $sourceColumn = $model->slugSource ?? 'name';  // Default to 'name' if slugSource is not set
            // Only update the slug if the source column value has changed
            if ($model->isDirty($sourceColumn)) {
                $model->slug = self::generateUniqueSlug($model->$sourceColumn);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
