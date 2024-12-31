<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileHandler
{

    protected $disk;

    public function __construct($disk = null)
    {
        $this->disk = $disk ?? config('filesystems.default');
    }

    /**
     * Store a file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string $disk
     * @return string The file path.
     */
    public function storeFile($file, $path)
    {
        return $file->store($path, $this->disk);
    }

    /**
     * Get the URL of a file.
     *
     * @param string $path
     * @param string $disk
     * @return string
     */
    public function getFileUrl($path)
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Delete a file.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function deleteFile($path)
    {
        return Storage::disk($this->disk)->delete($path);
    }

    /**
     * Check if a file exists.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function fileExists($path)
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Move a file to a new location.
     *
     * @param string $from
     * @param string $to
     * @param string $disk
     * @return bool
     */
    public function moveFile($from, $to)
    {
        return Storage::disk($this->disk)->move($from, $to);
    }

    /**
     * Copy a file to a new location.
     *
     * @param string $from
     * @param string $to
     * @param string $disk
     * @return bool
     */
    public function copyFile($from, $to)
    {
        return Storage::disk($this->disk)->copy($from, $to);
    }
}
