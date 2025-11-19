<?php

namespace App\Services\Interfaces;

interface UploaderInterface
{
    public function upload($file): string;
}
