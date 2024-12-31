<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('getUrl')) {
    /**
     * Get the full URL for a file from storage.
     *
     * @param string|null $path
     * @return string
     */
    function getUrl(?string $path): string
    {
        if (!$path) {
            return asset('images/default.png');
        }

        return Storage::disk(config('filesystems.default'))->url($path);
    }
}
