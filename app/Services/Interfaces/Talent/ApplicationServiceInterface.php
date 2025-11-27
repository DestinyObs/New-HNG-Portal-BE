<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Talent;

use Illuminate\Http\Request;

interface ApplicationServiceInterface
{
    public function listApplications(): object|array;
    public function createApplication(array $data, Request $request): object|array;
    public function getSingleApplication(string $appId): object|array;
    public function withdrawApplication(string $appId): object|array;
}