<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Admin;

use App\Models\User;
use Illuminate\Http\Request;

interface StateServiceInterface
{
    public function getAll(): object|array;
}