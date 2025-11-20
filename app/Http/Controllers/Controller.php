<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Traits\UploadFile;

abstract class Controller
{
    use ApiResponse, UploadFile;
}
