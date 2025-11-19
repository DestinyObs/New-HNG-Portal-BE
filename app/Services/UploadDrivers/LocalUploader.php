<?php

namespace App\Services\UploadDrivers;

use App\Services\Interfaces\UploaderInterface;
use Illuminate\Support\Facades\Storage;

class LocalUploader
{
    public function upload($file): string
    {
        $folder = config('company.logo_folder');
        $path = $file->store($folder, 'public');

        return Storage::disk('public')->url($path);
    }
}
