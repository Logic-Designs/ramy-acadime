<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;


class ImageService
{
    protected $disk;
    protected $manager;

    public function __construct($disk = null)
    {
        $this->disk = $disk ?? config('filesystems.default');

        // $this->manager = new ImageManager(new Driver());
    }

    /**
     * Store the uploaded image.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $path
     * @param array|null $resizeDimensions ['width' => int, 'height' => int]
     * @return string Image path
     */
    public function store($image, $path = 'images', $resizeDimensions = null)
    {
        $filename = uniqid() . '.webp';

        Storage::disk($this->disk)->putFileAs('uploads/'.$path, $image, $filename);


        return "uploads/$path/$filename";
    }

    /**
     * Delete an image.
     *
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        return Storage::disk($this->disk)->exists($path)
            ? Storage::disk($this->disk)->delete($path)
            : false;
    }

    /**
     * Get the image URL.
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Store multiple images.
     *
     * @param array $images
     * @param string $path
     * @param array|null $resizeDimensions
     * @return array
     */
    public function storeMultiple(array $images, $path = 'images', $resizeDimensions = null)
    {
        $storedPaths = [];

        foreach ($images as $image) {
            $storedPaths[] = $this->store($image, $path, $resizeDimensions);
        }

        return $storedPaths;
    }

    /**
     * Set the storage disk dynamically (e.g., public, spaces).
     *
     * @param string $disk
     * @return $this
     */
    public function setDisk($disk)
    {
        $this->disk = $disk;
        return $this;
    }
}
