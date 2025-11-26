<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Admin;

interface JobLevelServiceInterface
{
    public function getAllJobLevels(): object|array;
}