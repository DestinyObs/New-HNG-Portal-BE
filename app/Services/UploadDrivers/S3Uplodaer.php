<?php

namespace App\Services\UploadDrivers;

use App\Services\Interfaces\UploaderInterface;
use Illuminate\Support\Facades\Storage;

class S3Uplodaer
{
    public function upload($file): string
    {
        $folder = config('company.logo_folder');
        $path = $file->store($folder, 's3');

        return Storage::disk('s3')->url($path);
    }
}
