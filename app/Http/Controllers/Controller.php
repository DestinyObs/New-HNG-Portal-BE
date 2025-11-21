<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse as TraitsApiResponse;
use App\Http\Controllers\Concerns\ApiResponse as ConcernsApiResponse;
use App\Traits\UploadFile;

abstract class Controller
{
    use ApiResponse, UploadFile;
}
