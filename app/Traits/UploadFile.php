<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

trait UploadFile
{
    /**
     * Upload images, PDFs, and documents.
     * Images will be compressed automatically.
     */
    public function uploadFile(UploadedFile $file, string $folder = 'uploads', ?string $oldPath = null): string
    {
        // ? Delete old file if provided
        if ($oldPath) {
            $this->deleteFile($oldPath);
        }

        $extension = strtolower($file->getClientOriginalExtension());

        // List of supported image formats
        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        // If it's an image → compress
        if (in_array($extension, $imageExtensions)) {
            return $this->compressAndUploadImage($file, $folder);
        }

        // If it's a PDF → upload normally
        if ($extension === 'pdf') {
            return $this->uploadDocument($file, $folder);
        }

        // Any other document → upload normally
        return $this->uploadDocument($file, $folder);
    }

    /**
     * Compress image before upload.
     */
    protected function compressAndUploadImage(UploadedFile $file, string $folder, int $maxHeight = 300, int $maxWidth = 300): string
    {
        $image = Image::make($file);

        // Resize the image while maintaining aspect ratio
        $image->resize($maxWidth, $maxHeight, function ($constraint) {
            $constraint->aspectRatio();   // maintain aspect ratio
            $constraint->upsize();        // prevent upsizing
        });

        // ? encode image to reduce quality
        $image->encode($file->getClientOriginalExtension(), 75); // 75% quality

        $filename = uniqid().'.'.$file->getClientOriginalExtension();
        $path = $folder.'/'.$filename;

        // ? check if folder exists and create it if not
        if (! Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        Storage::disk('public')->put($path, $image);

        return $path;
    }

    /**
     * Upload PDF or any other document without compression.
     */
    protected function uploadDocument(UploadedFile $file, string $folder): string
    {
        if (! Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        return $file->store($folder, 'public');
    }

    /**
     * Delete file from storage.
     */
    public function deleteFile(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
